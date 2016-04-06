<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\Ratepay\Business\Payment\Method;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Ratepay\RatepayConstants;

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
        }
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
