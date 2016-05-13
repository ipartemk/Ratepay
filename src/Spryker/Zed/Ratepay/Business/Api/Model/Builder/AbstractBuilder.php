<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\Ratepay\Business\Api\Model\Builder;

use Generated\Shared\Transfer\RatepayRequestTransfer;
use Spryker\Zed\Ratepay\Business\Api\Model\AbstractRequest;

/**
 * @package Spryker\Zed\Ratepay\Business\Api\Model\Builder
 */
abstract class AbstractBuilder extends AbstractRequest
{

    /**
     * @var \Generated\Shared\Transfer\RatepayRequestTransfer
     */
    protected $requestTransfer;

    /**
     * @param \Generated\Shared\Transfer\RatepayRequestTransfer $requestTransfer
     */
    public function __construct(RatepayRequestTransfer $requestTransfer)
    {
        $this->requestTransfer = $requestTransfer;
    }

}
