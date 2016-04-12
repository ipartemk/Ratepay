<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\Ratepay\Business\Payment\Method;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RatepayResponseTransfer;
use Orm\Zed\Ratepay\Persistence\SpyPaymentRatepayQuery;
use Psr\Log\LoggerInterface;
use Spryker\Zed\Ratepay\Business\Api\Adapter\AdapterInterface;
use Spryker\Zed\Ratepay\Business\Api\Constants as ApiConstants;
use Spryker\Zed\Ratepay\Business\Api\Converter\ConverterInterface;
use Spryker\Zed\Ratepay\Business\Api\Model\RequestModelFactoryInterface;
use Spryker\Zed\Ratepay\Business\Api\Model\Response\BaseResponse;

abstract class AbstractMethod implements MethodInterface
{

    /**
     * @var \Spryker\Zed\Ratepay\Business\Api\Adapter\AdapterInterface
     */
    protected $adapter;

    /**
     * @var \Spryker\Zed\Ratepay\Business\Api\Model\RequestModelFactoryInterface
     */
    protected $modelFactory;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var \Spryker\Zed\Ratepay\Business\Api\Converter\ConverterInterface
     */
    protected $converter;

    /**
     * @param \Spryker\Zed\Ratepay\Business\Api\Adapter\AdapterInterface $adapter
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\RequestModelFactoryInterface $modelFactory
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Spryker\Zed\Ratepay\Business\Api\Converter\ConverterInterface $converter
     *
     */
    public function __construct(
        AdapterInterface $adapter,
        RequestModelFactoryInterface $modelFactory,
        LoggerInterface $logger,
        ConverterInterface $converter
    ) {
        $this->adapter = $adapter;
        $this->modelFactory = $modelFactory;
        $this->logger = $logger;
        $this->converter = $converter;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function paymentRequest(QuoteTransfer $quoteTransfer)
    {
        $paymentData = $this->getPaymentData($quoteTransfer);

        if ($paymentData->getTransactionId() == '') {
            $initResponse = $this->paymentInit();
            if (!$initResponse->getSuccessful()) {
                return $initResponse;
            }
            $paymentData->setTransactionId($initResponse->getTransactionId())
                ->setTransactionShortId($initResponse->getTransactionShortId())
                ->setResultCode($initResponse->getResultCode());
        }

        /**
         * @var \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Request $request
         */
        $request = $this->modelFactory->build(ApiConstants::REQUEST_MODEL_PAYMENT_REQUEST);

        $this->mapPaymentData($quoteTransfer, $paymentData, $request);

        $response = $this->sendRequest((string)$request);

        $this->logDebug(ApiConstants::REQUEST_MODEL_PAYMENT_REQUEST, $request, $response);
        $responseTransfer = $this->converter->responseToTransferObject($response);
        $this->fixResponseTransferTransactionId($responseTransfer, $paymentData->getTransactionId(), $paymentData->getTransactionShortId());
        return $responseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    abstract protected function getPaymentData(QuoteTransfer $quoteTransfer);

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\RatepayPaymentElvTransfer $paymentData
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Request $request
     *
     * @return void
     */
    protected function mapPaymentData($quoteTransfer, $paymentData, $request)
    {
        $request->getHead()->setTransactionId($paymentData->getTransactionId())->setTransactionShortId($paymentData->getTransactionShortId());
        $this->converter->mapPayment($quoteTransfer, $paymentData, $request->getPayment());
        $this->converter->mapCustomer($quoteTransfer, $paymentData, $request->getCustomer());
        $this->mapShoppingBasketAndItems($quoteTransfer, $paymentData, $request);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\RatepayPaymentElvTransfer $paymentData
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Request $request
     *
     * @return void
     */
    protected function mapBankAccountData($quoteTransfer, $paymentData, $request)
    {
        $bankAccount = $this->modelFactory->build(ApiConstants::REQUEST_MODEL_BANK_ACCOUNT);
        $request->getCustomer()->setBankAccount($bankAccount);
        $this->converter->mapBankAccount($quoteTransfer, $paymentData, $request->getCustomer()->getBankAccount());
    }

    /**
     * @return \Generated\Shared\Transfer\RatepayResponseTransfer
     */
    public function paymentInit()
    {
        /**
         * @var \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Init $request
         */
        $request = $this->modelFactory->build(ApiConstants::REQUEST_MODEL_PAYMENT_INIT);
        $response = $this->sendRequest((string)$request);

        $this->logDebug(ApiConstants::REQUEST_MODEL_PAYMENT_INIT, $request, $response);

        return $this->converter->responseToTransferObject($response);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\RatepayResponseTransfer
     */
    public function paymentConfirm(OrderTransfer $orderTransfer)
    {
        $confirmationModelType = ApiConstants::REQUEST_MODEL_PAYMENT_CONFIRM;
        $payment = $this->loadOrderPayment($orderTransfer);

        /**
         * @var \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Confirm $request
         */
        $request = $this->modelFactory->build($confirmationModelType);
        $request->getHead()->setTransactionId($payment->getTransactionId())->setTransactionShortId($payment->getTransactionShortId());

        $response = $this->sendRequest((string)$request);
        $this->logDebug($confirmationModelType, $request, $response);

        if ($response->isSuccessful()) {
            $payment->setResultCode($response->getResultCode())->save();
        }
        return $this->converter->responseToTransferObject($response);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\RatepayResponseTransfer
     */
    public function deliveryConfirm(OrderTransfer $orderTransfer)
    {
        $confirmationModelType = ApiConstants::REQUEST_MODEL_DELIVER_CONFIRM;
        $payment = $this->loadOrderPayment($orderTransfer);
        $paymentData = $this->getTransferObjectFromPayment($payment);

        /**
         * @var \Spryker\Zed\Ratepay\Business\Api\Model\Deliver\Confirm $request
         */
        $request = $this->modelFactory->build($confirmationModelType);
        $request->getHead()->setTransactionId($payment->getTransactionId())->setTransactionShortId($payment->getTransactionShortId());
        $this->mapShoppingBasketAndItems($orderTransfer, $paymentData, $request);

        $response = $this->sendRequest((string)$request);
        $this->logDebug($confirmationModelType, $request, $response);

        if ($response->isSuccessful()) {
            $payment->setResultCode($response->getResultCode())->save();
        }
        return $this->converter->responseToTransferObject($response);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\RatepayResponseTransfer
     */
    public function paymentCancel(OrderTransfer $orderTransfer)
    {
        $cancellationModelType = ApiConstants::REQUEST_MODEL_PAYMENT_CANCEL;
        $payment = $this->loadOrderPayment($orderTransfer);
        $paymentData = $this->getTransferObjectFromPayment($payment);

        /**
         * @var \Spryker\Zed\Ratepay\Business\Api\Model\Deliver\Confirm $request
         */
        $request = $this->modelFactory->build($cancellationModelType);
        $request->getHead()->setTransactionId($payment->getTransactionId())->setTransactionShortId($payment->getTransactionShortId());
        $request->getHead()->setExternalOrderId($orderTransfer->requireOrderReference()->getOrderReference());
        $this->mapShoppingBasketAndItems($orderTransfer, $paymentData, $request);

        $response = $this->sendRequest((string)$request);
        $this->logDebug($cancellationModelType, $request, $response);

        if ($response->isSuccessful()) {
            $payment->setResultCode($response->getResultCode())->save();
        }

        return $this->converter->responseToTransferObject($response);
    }

    public function paymentRefund(OrderTransfer $orderTransfer)
    {
        $orderRefundModelType = ApiConstants::REQUEST_MODEL_PAYMENT_REFUND;
        $payment = $this->loadOrderPayment($orderTransfer);
        $paymentData = $this->getTransferObjectFromPayment($payment);

        /**
         * @var \Spryker\Zed\Ratepay\Business\Api\Model\Deliver\Confirm $request
         */
        $request = $this->modelFactory->build($orderRefundModelType);
        $request->getHead()->setTransactionId($payment->getTransactionId())->setTransactionShortId($payment->getTransactionShortId());
        $request->getHead()->setExternalOrderId($orderTransfer->requireOrderReference()->getOrderReference());
        $this->mapShoppingBasketAndItems($orderTransfer, $paymentData, $request);

        $response = $this->sendRequest((string)$request);
        $this->logDebug($orderRefundModelType, $request, $response);

        if ($response->isSuccessful()) {
            $payment->setResultCode($response->getResultCode())->save();
        }

        return $this->converter->responseToTransferObject($response);
    }

    /**
     * @param \Orm\Zed\Ratepay\Persistence\SpyPaymentRatepay $payment
     *
     * @return mixed
     */
    protected function getTransferObjectFromPayment($payment)
    {
        $paymentTransfer = $this->getPaymentTransferObject($payment);
        $paymentTransfer->fromArray($payment->toArray(), true);

        return $paymentTransfer;
    }

    /**
     * @param \Orm\Zed\Ratepay\Persistence\SpyPaymentRatepay $payment
     *
     * @return mixed
     */
    abstract protected function getPaymentTransferObject($payment);

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer|\Generated\Shared\Transfer\QuoteTransfer $dataTransfer
     * @param $paymentData
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Request $request
     *
     * @return void
     */
    protected function mapShoppingBasketAndItems($dataTransfer, $paymentData, $request)
    {
        $this->converter->mapBasket($dataTransfer, $paymentData, $request->getShoppingBasket());
        $basketItems = $dataTransfer->requireItems()->getItems();
        foreach ($basketItems as $basketItem) {
            $shoppingBasketItem = $this->modelFactory->build(ApiConstants::REQUEST_MODEL_BASKET_ITEM);
            $this->converter->mapBasketItem($basketItem, $shoppingBasketItem);
            $request->getShoppingBasket()->addItem($shoppingBasketItem);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isPreAuthorizationApproved(OrderTransfer $orderTransfer)
    {
        $payment = $this->loadOrderPayment($orderTransfer);
        return in_array(
            $payment->getResultCode(),
            [
                ApiConstants::REQUEST_CODE_SUCCESS_MATRIX[ApiConstants::REQUEST_MODEL_PAYMENT_CONFIRM],
                ApiConstants::REQUEST_CODE_SUCCESS_MATRIX[ApiConstants::REQUEST_MODEL_DELIVER_CONFIRM],
            ]
        );
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isCaptureApproved(OrderTransfer $orderTransfer)
    {
        $payment = $this->loadOrderPayment($orderTransfer);
        return in_array(
            $payment->getResultCode(),
            [
                ApiConstants::REQUEST_CODE_SUCCESS_MATRIX[ApiConstants::REQUEST_MODEL_DELIVER_CONFIRM],
            ]
        );
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isCancellationConfirmed(OrderTransfer $orderTransfer)
    {
        $payment = $this->loadOrderPayment($orderTransfer);
        return in_array(
            $payment->getResultCode(),
            [
                ApiConstants::REQUEST_CODE_SUCCESS_MATRIX[ApiConstants::REQUEST_MODEL_PAYMENT_CHANGE],
            ]
        );
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isRefundApproved(OrderTransfer $orderTransfer)
    {
        $payment = $this->loadOrderPayment($orderTransfer);
        return in_array(
            $payment->getResultCode(),
            [
                ApiConstants::REQUEST_CODE_SUCCESS_MATRIX[ApiConstants::REQUEST_MODEL_PAYMENT_CHANGE],
            ]
        );
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Orm\Zed\Ratepay\Persistence\SpyPaymentRatepay
     */
    protected function loadOrderPayment(OrderTransfer $orderTransfer)
    {
        $query = new SpyPaymentRatepayQuery();
        return $query->findByFkSalesOrder($orderTransfer->requireIdSalesOrder()->getIdSalesOrder())->getFirst();
    }

    /**
     * @param string $request
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Response\BaseResponse
     */
    protected function sendRequest($request)
    {
        return new BaseResponse($this->adapter->sendRequest($request));
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
    protected function logDebug($message, $request, $response)
    {
        $this->logger->debug(
            $message,
            [
                'request_transaction_id' => $request->getHead()->getTransactionId(),
                'request_type' => $request->getHead()->getOperation(),

                'response_result_code' => $response->getResultCode(),
                'response_result_text' => $response->getResultText(),
                'response_transaction_id' => $response->getTransactionId(),
                'response_transaction_short_id' => $response->getTransactionShortId(),
                'response_reason_code' => $response->getReasonCode(),
                'response_reason_text' => $response->getReasonText(),
                'response_status_code' => $response->getStatusCode(),
                'response_status_text' => $response->getStatusText(),

                'request_body' => (string)$request,
            ]
        );
    }

}
