<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Order\MethodMapper\Transaction;

use Generated\Shared\Transfer\QuoteTransfer;

interface TransactionInterface
{

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Zed\Ratepay\Business\Order\MethodMapper\PaymentMethodMapperInterface
     */
    public function prepareMethodMapper(QuoteTransfer $quoteTransfer);

}
