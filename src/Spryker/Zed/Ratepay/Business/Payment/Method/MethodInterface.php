<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\Ratepay\Business\Payment\Method;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface MethodInterface
{

    public function paymentInit(QuoteTransfer $quoteTransfer);

    public function paymentRequest(QuoteTransfer $quoteTransfer);

    public function paymentConfirm(OrderTransfer $orderTransfer);

    public function deliveryConfirm(OrderTransfer $orderTransfer);

    public function paymentChange(OrderTransfer $orderTransfer);

}
