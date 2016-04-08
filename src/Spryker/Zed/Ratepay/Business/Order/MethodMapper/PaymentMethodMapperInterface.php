<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Order\MethodMapper;

use Orm\Zed\Ratepay\Persistence\SpyPaymentRatepay;
use Generated\Shared\Transfer\QuoteTransfer;

interface PaymentMethodMapperInterface
{
    public function getMethodName();

    /**
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param SpyPaymentRatepay $payment
     */
    public function mapMethodDataToPayment(QuoteTransfer $quoteTransfer, SpyPaymentRatepay $payment);

}
