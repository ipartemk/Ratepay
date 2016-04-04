<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\Ratepay\Business\Payment\Method;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Ratepay\Business\Api\Adapter\AdapterInterface;
use Spryker\Zed\Ratepay\Business\Api\Constants as ApiConstants;
use Spryker\Zed\Ratepay\Business\Api\Model\FactoryInterface;
use Spryker\Zed\Ratepay\Business\Payment\Method\MethodInterface;

abstract class MethodAbstract implements MethodInterface
{

    /**
     * @var \Spryker\Zed\Ratepay\Business\Api\Adapter\AdapterInterface
     */
    protected $adapter;

    /**
     * @var \Spryker\Zed\Ratepay\Business\Api\Model\FactoryInterface
     */
    protected $modelFactory;

    /**
     * @param \Spryker\Zed\Ratepay\Business\Api\Adapter\AdapterInterface $adapter
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\FactoryInterface $modelFactory
     *
     */
    public function __construct(AdapterInterface $adapter, FactoryInterface $modelFactory)
    {
        $this->adapter = $adapter;
        $this->modelFactory = $modelFactory;
    }

    public function paymentInit(QuoteTransfer $quoteTransfer)
    {
        $request = $this->modelFactory->build(ApiConstants::REQUEST_MODEL_PAYMENT_INIT);
        $result = $this->adapter->sendRequest((string)$request);
    }

}
