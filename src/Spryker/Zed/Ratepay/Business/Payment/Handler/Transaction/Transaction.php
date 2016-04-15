<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Payment\Handler\Transaction;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RatepayResponseTransfer;
use Psr\Log\LoggerInterface;
use Spryker\Zed\Ratepay\Business\Api\Adapter\AdapterInterface;
use Spryker\Zed\Ratepay\Business\Api\Constants as ApiConstants;
use Spryker\Zed\Ratepay\Business\Api\Converter\ConverterInterface;
use Spryker\Zed\Ratepay\Business\Api\Model\Payment\Request as PyamentRequest;
use Spryker\Zed\Ratepay\Business\Api\Model\Response\BaseResponse;
use Spryker\Zed\Ratepay\Business\Exception\NoMethodMapperException;
use Spryker\Zed\Ratepay\Business\Payment\Method\MethodInterface;
use Spryker\Zed\Ratepay\Persistence\RatepayQueryContainerInterface;

class Transaction implements TransactionInterface
{

    /**
     * @var \Spryker\Zed\Ratepay\Business\Api\Adapter\AdapterInterface
     */
    protected $executionAdapter;

    /**
     * @var \Spryker\Zed\Ratepay\Business\Api\Converter\ConverterInterface
     */
    protected $converter;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var \Spryker\Zed\Ratepay\Persistence\RatepayQueryContainerInterface $queryContainer
     */
    protected $queryContainer;

    /**
     * @var array
     */
    protected $methodMappers = [];

    /**
     * @param \Spryker\Zed\Ratepay\Business\Api\Adapter\AdapterInterface $executionAdapter
     * @param \Spryker\Zed\Ratepay\Business\Api\Converter\ConverterInterface $converter
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Spryker\Zed\Ratepay\Persistence\RatepayQueryContainerInterface $queryContainer
     */
    public function __construct(
        AdapterInterface $executionAdapter,
        ConverterInterface $converter,
        LoggerInterface $logger,
        RatepayQueryContainerInterface $queryContainer
    ) {
        $this->executionAdapter = $executionAdapter;
        $this->converter = $converter;
        $this->logger = $logger;
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\RatepayResponseTransfer
     */
    public function preCheckPayment(QuoteTransfer $quoteTransfer)
    {
        $paymentMethod = $quoteTransfer
            ->requirePayment()
            ->getPayment()
            ->requirePaymentMethod()
            ->getPaymentMethod();

        //init payment method call.
        $request = $this->getMethodMapper($paymentMethod)
            ->paymentInit();
        $response = $this->sendRequest((string)$request);
        $this->logInfo(ApiConstants::REQUEST_MODEL_PAYMENT_INIT, $request, $response);
        $initResponseTransfer = $this->converter->responseToTransferObject($response);

        if (!$initResponseTransfer->getSuccessful()) {
            return $initResponseTransfer;
        }

        //payment request call.
        $transactionId = $initResponseTransfer->requireTransactionId()->getTransactionId();
        $transactionShortId = $initResponseTransfer->requireTransactionShortId()->getTransactionShortId();
        $resultCode = $initResponseTransfer->requireResultCode()->getResultCode();
        $request = $this->getMethodMapper($paymentMethod)
            ->paymentRequest($quoteTransfer, $transactionId, $transactionShortId, $resultCode);

        $response = $this->sendRequest((string)$request);
        $this->logInfo(ApiConstants::REQUEST_MODEL_PAYMENT_REQUEST, $request, $response);

        $responseTransfer = $this->converter->responseToTransferObject($response);
        $this->fixResponseTransferTransactionId($responseTransfer, $responseTransfer->getTransactionId(), $responseTransfer->getTransactionShortId());

        return $responseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\RatepayResponseTransfer
     */
    public function preAuthorizePayment(OrderTransfer $orderTransfer)
    {
        $paymentMethod = $this->getPaymentMethod($orderTransfer);
        $request = $this
            ->getMethodMapper($paymentMethod->getPaymentType())
            ->paymentConfirm($orderTransfer);

        $response = $this->sendRequest((string)$request);
        $this->logInfo(ApiConstants::REQUEST_MODEL_PAYMENT_CONFIRM, $request, $response);

        if ($response->isSuccessful()) {
            $paymentMethod->setResultCode($response->getResultCode())->save();
        }
        return $this->converter->responseToTransferObject($response);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\RatepayResponseTransfer
     */
    public function capturePayment(OrderTransfer $orderTransfer)
    {
        $paymentMethod = $this->getPaymentMethod($orderTransfer);
        $request = $this
            ->getMethodMapper($paymentMethod->getPaymentType())
            ->deliveryConfirm($orderTransfer);

        $response = $this->sendRequest((string)$request);
        $this->logInfo(ApiConstants::REQUEST_MODEL_DELIVER_CONFIRM, $request, $response);

        if ($response->isSuccessful()) {
            $paymentMethod->setResultCode($response->getResultCode())->save();
        }

        return $this->converter->responseToTransferObject($response);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\RatepayResponseTransfer
     */
    public function cancelPayment(OrderTransfer $orderTransfer)
    {
        $paymentMethod = $this->getPaymentMethod($orderTransfer);
        $request = $this
            ->getMethodMapper($paymentMethod->getPaymentType())
            ->paymentCancel($orderTransfer);

        $response = $this->sendRequest((string)$request);
        $this->logInfo(ApiConstants::REQUEST_MODEL_PAYMENT_CANCEL, $request, $response);

        if ($response->isSuccessful()) {
            $paymentMethod->setResultCode($response->getResultCode())->save();
        }

        return $this->converter->responseToTransferObject($response);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\RatepayResponseTransfer
     */
    public function refundPayment(OrderTransfer $orderTransfer)
    {
        $paymentMethod = $this->getPaymentMethod($orderTransfer);
        $request = $this
            ->getMethodMapper($paymentMethod->getPaymentType())
            ->paymentRefund($orderTransfer);

        $response = $this->sendRequest((string)$request);
        $this->logInfo(ApiConstants::REQUEST_MODEL_PAYMENT_REFUND, $request, $response);

        if ($response->isSuccessful()) {
            $paymentMethod->setResultCode($response->getResultCode())->save();
        }

        return $this->converter->responseToTransferObject($response);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Orm\Zed\Ratepay\Persistence\SpyPaymentRatepay
     */
    protected function getPaymentMethod(OrderTransfer $orderTransfer)
    {
        return $this->queryContainer
            ->queryPayments()
            ->findByFkSalesOrder(
                $orderTransfer->requireIdSalesOrder()->getIdSalesOrder()
            )->getFirst();
    }

    /**
     * @param \Spryker\Zed\Ratepay\Business\Payment\Method\MethodInterface $mapper
     *
     * @return void
     */
    public function registerMethodMapper(MethodInterface $mapper)
    {
        $this->methodMappers[$mapper->getMethodName()] = $mapper;
    }

    /**
     * @param string $accountBrand
     *
     * @throws \Spryker\Zed\Ratepay\Business\Exception\NoMethodMapperException
     *
     * @return \Spryker\Zed\Ratepay\Business\Payment\Method\MethodInterface
     */
    protected function getMethodMapper($accountBrand)
    {
        if (isset($this->methodMappers[$accountBrand]) === false) {
            throw new NoMethodMapperException('The method mapper is not registered.');
        }

        return $this->methodMappers[$accountBrand];
    }

    /**
     * @param string $request
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Response\BaseResponse
     */
    protected function sendRequest($request)
    {
        return new BaseResponse($this->executionAdapter->sendRequest($request));
    }

    /**
     * According to the documentation the transaction ID is always returned, if it was sent, but it is not the fact for
     * error cases, therefore we have to set transaction ID, so it is not lost after each error.
     *
     * @param \Generated\Shared\Transfer\RatepayResponseTransfer $responseTransfer
     * @param string $transId
     * @param string $transShortId
     *
     * @return void
     */
    protected function fixResponseTransferTransactionId(RatepayResponseTransfer $responseTransfer, $transId, $transShortId)
    {
        if ($responseTransfer->getTransactionId() === '' && $transId !== '') {
            $responseTransfer->setTransactionId($transId)->setTransactionShortId($transShortId);
        }
    }

    /**
     * @param string $message
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Base $request
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Response\ResponseInterface $response
     *
     * @return void
     */
    protected function logInfo($message, $request, $response)
    {
        $context = [
            'order_id' => $request->getHead()->getOrderId(),

            'payment_method' => null,
            'request_type' => $request->getHead()->getOperation(),
            'request_transaction_id' => $request->getHead()->getTransactionId(),
            'request_transaction_short_id' => $request->getHead()->getTransactionShortId(),
            'request_body' => (string)$request,

            'response_type' => $response->getResponseType(),
            'response_result_code' => $response->getResultCode(),
            'response_result_text' => $response->getResultText(),
            'response_transaction_id' => $response->getTransactionId(),
            'response_transaction_short_id' => $response->getTransactionShortId(),
            'response_reason_code' => $response->getReasonCode(),
            'response_reason_text' => $response->getReasonText(),
            'response_status_code' => $response->getStatusCode(),
            'response_status_text' => $response->getStatusText(),
            'response_customer_message' => $response->getCustomerMessage(),
        ];
        if ($request instanceof PyamentRequest) {
            $context['payment_method'] = $request->getPayment()->getMethod();
        }

        $this->logger->info($message, $context);
    }

}
