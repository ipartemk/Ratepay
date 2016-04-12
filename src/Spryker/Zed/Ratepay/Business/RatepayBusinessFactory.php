<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

use Spryker\Shared\Ratepay\RatepayConstants;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Ratepay\Business\Api\Adapter\Http\Guzzle;
use Spryker\Zed\Ratepay\Business\Api\ApiFactory;
use Spryker\Zed\Ratepay\Business\Api\Converter\Converter as Converter;
use Spryker\Zed\Ratepay\Business\Order\MethodMapperFactory;
use Spryker\Zed\Ratepay\Business\Order\Saver as Saver;
use Spryker\Zed\Ratepay\Business\Payment\Handler\Transaction\Transaction;
use Spryker\Zed\Ratepay\Business\Payment\Method\Elv as Elv;
use Spryker\Zed\Ratepay\Business\Payment\Method\Invoice as Invoice;
use Spryker\Zed\Ratepay\Business\Payment\Method\Prepayment as Prepayment;

/**
 * @method \Spryker\Zed\Ratepay\Persistence\RatepayQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Ratepay\RatepayConfig getConfig()
 */
class RatepayBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @param string $gatewayUrl
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Adapter\AdapterInterface
     */
    protected function createAdapter($gatewayUrl)
    {
        return new Guzzle($gatewayUrl);
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Payment\Handler\Transaction\TransactionInterface
     */
    public function createPaymentTransactionHandler()
    {
        $paymentTransactionHandler = new Transaction();
        $paymentTransactionHandler->registerMethodMapper($this->createInvoice());
        $paymentTransactionHandler->registerMethodMapper($this->createElv());
        $paymentTransactionHandler->registerMethodMapper($this->createPrepayment());

        return $paymentTransactionHandler;
    }

    /**
     * @return Api\Model\RequestModelFactoryInterface
     */
    public function createApiRequestFactory()
    {
        $factory = new ApiFactory();

        return $factory->createRequestModelFactory();
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Order\MethodMapperFactory
     */
    public function getMethodMapperFactory()
    {
        return new MethodMapperFactory();
    }

    /**
     * @return \Monolog\Handler\StreamHandler
     */
    protected function createMonologWriter()
    {
        return new StreamHandler(RatepayConstants::LOGGER_STREAM_OUTPUT);
    }

    /**
     * @return \Monolog\Logger
     */
    protected function createMonolog()
    {
        $log = new Logger('ratepay');
        $log->pushHandler($this->createMonologWriter());

        return $log;
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Order\SaverInterface
     */
    public function createOrderSaver()
    {
        return new Saver();
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Api\Converter\Converter
     */
    protected function createConverter()
    {
        return new Converter();
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Payment\Method\Invoice
     */
    public function createInvoice()
    {
        return new Invoice(
            $this->createAdapter($this->getConfig()->getTransactionGatewayUrl()),
            $this->createApiRequestFactory(),
            $this->createMonolog(),
            $this->createConverter()
        );
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Payment\Method\Elv
     */
    public function createElv()
    {
        return new Elv(
            $this->createAdapter($this->getConfig()->getTransactionGatewayUrl()),
            $this->createApiRequestFactory(),
            $this->createMonolog(),
            $this->createConverter()
        );
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Payment\Method\Prepayment
     */
    public function createPrepayment()
    {
        return new Prepayment(
            $this->createAdapter($this->getConfig()->getTransactionGatewayUrl()),
            $this->createApiRequestFactory(),
            $this->createMonolog(),
            $this->createConverter()
        );
    }

}
