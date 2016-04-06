<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\Ratepay\Business\Payment\Method;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Ratepay\RatepayConstants;
use Spryker\Zed\Ratepay\Business\Api\Constants as ApiConstants;

class Invoice extends AbstractMethod
{

    const METHOD = RatepayConstants::METHOD_INVOICE;

    public function getMethodName()
    {
        return static::METHOD;
    }

    public function paymentRequest(QuoteTransfer $quoteTransfer)
    {
        $paymentData = $quoteTransfer->requirePayment()->getPayment()->requireRatepayInvoice()->getRatepayInvoice();

        if ($paymentData->getTransactionId() == '') {
            $initResponse = $this->paymentInit();
            if (!$initResponse->getIsSuccessfull()) {
                return $initResponse;
            }
            $paymentData->setTransactionId($initResponse->getTransactionId())->setTransactionShortId($initResponse->getTransactionShortId());
        }

        /**
         * @var \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Request $request
         */
        $request = $this->modelFactory->build(ApiConstants::REQUEST_MODEL_PAYMENT_REQUEST);

        $this->converter->mapCustomer($quoteTransfer, $request->getCustomer());
        $this->converter->mapBasket($quoteTransfer, $request->getShoppingBasket());

        $response = $this->sendRequest((string)$request);

        $this->logDebug(
            ApiConstants::REQUEST_MODEL_PAYMENT_INIT,
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
            ]
        );

        return $this->converter->responseToTransferObject($response);
    }

    public function paymentConfirm(OrderTransfer $orderTransfer)
    {

    }

    public function deliveryConfirm(OrderTransfer $orderTransfer)
    {

    }

    public function paymentChange(OrderTransfer $orderTransfer)
    {

    }

}
