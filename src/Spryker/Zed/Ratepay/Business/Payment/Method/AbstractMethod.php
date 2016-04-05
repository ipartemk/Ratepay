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
use Psr\Log\LoggerInterface;

abstract class AbstractMethod implements MethodInterface
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
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @param \Spryker\Zed\Ratepay\Business\Api\Adapter\AdapterInterface $adapter
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\FactoryInterface $modelFactory
     * @param \Psr\Log\LoggerInterface $logger
     *
     */
    public function __construct(
        AdapterInterface $adapter,
        FactoryInterface $modelFactory,
        LoggerInterface $logger
    ) {
        $this->adapter = $adapter;
        $this->modelFactory = $modelFactory;
        $this->logger = $logger;
    }

    public function paymentInit()
    {
        /**
         * @var \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Init $request
         */
        $request = $this->modelFactory->build(ApiConstants::REQUEST_MODEL_PAYMENT_INIT);


        $result = $this->adapter->sendRequest((string)$request);

        $this->logDebug(
            ApiConstants::REQUEST_MODEL_PAYMENT_INIT,
            [
                'request_transaction_id' => $request->getHead()->getTransactionId(),
                'request_type' => $request->getHead()->getOperation(),
            ]
        );

        return $result;
    }

    protected function logDebug($message, $context)
    {
        $this->logger->debug($message, $context);
    }
}
