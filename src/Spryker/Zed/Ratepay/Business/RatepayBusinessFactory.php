<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Ratepay\Business\Api\Adapter\Http\Guzzle;
use Spryker\Zed\Ratepay\Business\Api\Constants as ApiConstants;
use Spryker\Zed\Ratepay\Business\Api\Model\Factory as RequestModelFactory;

use Spryker\Zed\Ratepay\Business\Api\Model\Parts\Customer;
use Spryker\Zed\Ratepay\Business\Api\Model\Parts\Head;
use Spryker\Zed\Ratepay\Business\Api\Model\Parts\Payment;
use Spryker\Zed\Ratepay\Business\Api\Model\Parts\ShoppingBasket;
use Spryker\Zed\Ratepay\Business\Api\Model\Payment\Init as PaymentInit;
use Spryker\Zed\Ratepay\Business\Api\Model\Payment\Request as PaymentRequest;

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
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\FactoryInterface
     */
    public function createRequestModelFactory()
    {
        static $factory;
        if ($factory === null) {
            $configuration = $this->getConfig();
            $factory = new RequestModelFactory();
            $factory
                ->registerBuilder(
                    ApiConstants::REQUEST_MODEL_HEAD,
                    function () use ($configuration) {
                        return $this->createHeadModel($configuration);
                    }
                )
                ->registerBuilder(
                    ApiConstants::REQUEST_MODEL_BASKET,
                    function () {
                        return $this->createBasketModel();
                    }
                )
                ->registerBuilder(
                    ApiConstants::REQUEST_MODEL_CUSTOMER,
                    function () {
                        return $this->createCustomerModel();
                    }
                )
                ->registerBuilder(
                    ApiConstants::REQUEST_MODEL_PAYMENT,
                    function () {
                        return $this->createPaymentModel();
                    }
                )
                ->registerBuilder(
                    ApiConstants::REQUEST_MODEL_PAYMENT_INIT,
                    function () use ($factory) {
                        return $this->createInitModel($factory);
                    }
                )
                ->registerBuilder(
                    ApiConstants::REQUEST_MODEL_PAYMENT_REQUEST,
                    function () use ($factory) {
                        return $this->createPaymentRequestModel($factory);
                    }
                );
        }

        return $factory;
    }


    protected function createHeadModel($configuration)
    {
        return new Head(
            $configuration->getSystemId(),
            $configuration->getProfileId(),
            $configuration->getSecurityCode()
        );
    }

    protected function createBasketModel()
    {
        return new ShoppingBasket();
    }

    protected function createCustomerModel()
    {
        return new Customer();
    }

    protected function createPaymentModel()
    {
        return new Payment();
    }

    protected function createInitModel(RequestModelFactory $factory)
    {
        return new PaymentInit($factory->build(ApiConstants::REQUEST_MODEL_HEAD));
    }

    protected function createPaymentRequestModel(RequestModelFactory $factory)
    {
        return new PaymentRequest(
            $factory->build(ApiConstants::REQUEST_MODEL_HEAD),
            $factory->build(ApiConstants::REQUEST_MODEL_CUSTOMER),
            $factory->build(ApiConstants::REQUEST_MODEL_BASKET),
            $factory->build(ApiConstants::REQUEST_MODEL_PAYMENT)
        );
    }

}
