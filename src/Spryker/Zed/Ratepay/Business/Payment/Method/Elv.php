<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\Ratepay\Business\Payment\Method;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Ratepay\RatepayConstants;

class Elv extends AbstractMethod
{

    const METHOD = RatepayConstants::METHOD_ELV;

    public function getMethodName()
    {
        return static::METHOD;
    }

    protected function getPaymentData(QuoteTransfer $quoteTransfer)
    {
        return $quoteTransfer->requirePayment()->getPayment()->requireRatepayElv()->getRatepayElv();
    }

    protected function mapPaymentData($quoteTransfer, $paymentData, $request)
    {
        parent::mapPaymentData($quoteTransfer, $paymentData, $request);
        $this->mapBanKAccountData($quoteTransfer, $paymentData, $request);
    }

    public function paymentChange(OrderTransfer $orderTransfer)
    {

    }

}
