<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\Ratepay\Business\Payment\Method;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\RatepayResponseTransfer;
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
     * @var \Spryker\Zed\Ratepay\Business\Api\Model\FactoryInterface
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

    public function paymentConfirm(OrderTransfer $orderTransfer)
    {

    }

    public function deliveryConfirm(OrderTransfer $orderTransfer)
    {

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
