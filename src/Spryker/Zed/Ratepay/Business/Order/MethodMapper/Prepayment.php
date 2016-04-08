<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\Ratepay\Business\Order\MethodMapper;

use Generated\Shared\Transfer\RatepayPaymentPrepaymentTransfer;
use Spryker\Shared\Ratepay\RatepayConstants;
use Generated\Shared\Transfer\QuoteTransfer;

class Prepayment extends AbstractMapper
{

    const METHOD = RatepayConstants::METHOD_PREPAYMENT;

    public function getMethodName()
    {
        return static::METHOD;
    }

    /**
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return RatepayPaymentPrepaymentTransfer
     */
    protected function getPaymentTransfer(QuoteTransfer $quoteTransfer)
    {
        return $quoteTransfer->getPayment()->getRatepayPrepayment();
    }
}
