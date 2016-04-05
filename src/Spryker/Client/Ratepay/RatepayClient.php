<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Ratepay;

use Spryker\Client\Kernel\AbstractClient;
use Generated\Shared\Transfer\RatepayResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

/**
 * @method \Spryker\Client\Ratepay\RatepayFactory getFactory()
 */
class RatepayClient extends AbstractClient implements RatepayClientInterface
{
    /**
     * @api
     *
     * @inheritdoc
     */
    public function preauthorizePayment(QuoteTransfer $quoteTransfer)
    {
        return $this
            ->getFactory()
            ->createRatepayStub()
            ->preauthorizePayment($quoteTransfer);
    }

}
