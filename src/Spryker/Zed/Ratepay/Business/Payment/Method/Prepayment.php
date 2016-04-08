<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\Ratepay\Business\Payment\Method;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Ratepay\RatepayConstants;

class Prepayment extends AbstractMethod
{

    const METHOD = RatepayConstants::METHOD_PREPAYMENT;

    public function getMethodName()
    {
        return static::METHOD;
    }

    protected function getPaymentData(QuoteTransfer $quoteTransfer)
    {
        return $quoteTransfer->requirePayment()->getPayment()->requireRatepayPrepayment()->getRatepayPrepayment();
    }

    public function paymentChange(OrderTransfer $orderTransfer)
    {

    }

}