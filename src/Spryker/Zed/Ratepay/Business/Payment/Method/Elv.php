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

class Elv extends AbstractMethod
{

    const METHOD = RatepayConstants::METHOD_ELV;

    public function getMethodName()
    {
        return static::METHOD;
    }

    public function paymentRequest(QuoteTransfer $quoteTransfer)
    {
        $paymentData = $quoteTransfer->requirePayment()->getPayment()->requireRatepayElv()->getRatepayElv();

        if ($paymentData->getTransactionId() == '') {
            $initResponse = $this->paymentInit();
            if (!$initResponse->getSuccessful()) {
                return $initResponse;
            }
            $paymentData->setTransactionId($initResponse->getTransactionId())
                ->setTransactionShortId($initResponse->getTransactionShortId());
        }

        /**
         * @var \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Request $request
         */
        $request = $this->modelFactory->build(ApiConstants::REQUEST_MODEL_PAYMENT_REQUEST);

        $bankAccount = $this->modelFactory->build(ApiConstants::REQUEST_MODEL_BANK_ACCOUNT);
        $request->getCustomer()->setBankAccount($bankAccount);

        $this->converter->mapCustomer($quoteTransfer, $paymentData, $request->getCustomer());
        $this->converter->mapBasket($quoteTransfer, $paymentData, $request->getShoppingBasket());
        $this->converter->mapBankAccount($quoteTransfer, $paymentData, $request->getCustomer()->getBankAccount());

        $response = $this->sendRequest((string)$request);

        return $this->converter->responseToTransferObject($response);
    }

    public function paymentChange(OrderTransfer $orderTransfer)
    {

    }

}
