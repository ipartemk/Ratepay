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

    /**
     * @return string
     */
    public function getMethodName()
    {
        return static::METHOD;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @return \Generated\Shared\Transfer\RatepayResponseTransfer
     */
    public function paymentRequest(QuoteTransfer $quoteTransfer)
    {
        $paymentData = $quoteTransfer->requirePayment()->getPayment()->requireRatepayInvoice()->getRatepayInvoice();

        if ($paymentData->getTransactionId() == '') {
            $initResponse = $this->paymentInit();
            if (!$initResponse->getSuccessful()) {
                return $initResponse;
            }
            $paymentData->setTransactionId($initResponse->getTransactionId())->setTransactionShortId($initResponse->getTransactionShortId());
        }

        /**
         * @var \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Request $request
         */
        $request = $this->modelFactory->build(ApiConstants::REQUEST_MODEL_PAYMENT_REQUEST);
        $request->getHead()->setTransactionId($paymentData->getTransactionId())
            ->setTransactionShortId($paymentData->getTransactionShortId());

        $this->converter->mapCustomer($quoteTransfer, $paymentData, $request->getCustomer());
        $this->converter->mapBasket($quoteTransfer, $request->getShoppingBasket());

        $response = $this->sendRequest((string)$request);

        $this->logDebug(ApiConstants::REQUEST_MODEL_PAYMENT_REQUEST, $request, $response);
        return $this->converter->responseToTransferObject($response);
    }

    public function paymentChange(OrderTransfer $orderTransfer)
    {

    }

}
