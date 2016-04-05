<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Ratepay\Zed;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Ratepay\Zed\RatepayStubInterface;
use Spryker\Client\ZedRequest\ZedRequestClient;

class RatepayStub implements RatepayStubInterface
{

    /**
     * @var \Spryker\Client\ZedRequest\ZedRequestClient
     */
    protected $zedRequestClient;

    /**
     * @param \Spryker\Client\ZedRequest\ZedRequestClient $zedRequestClient
     */
    public function __construct(ZedRequestClient $zedRequestClient)
    {
        $this->zedRequestClient = $zedRequestClient;
    }

    /**
     * @inheritdoc
     */
    public function preauthorizePayment(QuoteTransfer $quoteTransfer)
    {
        return $this->zedRequestClient->call('/ratepay/gateway/preauthorize-payment', $quoteTransfer);
    }

}
