<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Ratepay;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Kernel\AbstractClient;

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
