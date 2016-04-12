<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Api;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Ratepay\Business\Api\Constants as ApiConstants;
use Spryker\Zed\Ratepay\Business\Api\Model\Deliver\Confirm as DeliverConfirm;
use Spryker\Zed\Ratepay\Business\Api\Model\Parts\Address;
use Spryker\Zed\Ratepay\Business\Api\Model\Parts\BankAccount;
use Spryker\Zed\Ratepay\Business\Api\Model\Parts\Customer;
use Spryker\Zed\Ratepay\Business\Api\Model\Parts\Head;
use Spryker\Zed\Ratepay\Business\Api\Model\Parts\Payment;
use Spryker\Zed\Ratepay\Business\Api\Model\Parts\ShoppingBasket;
use Spryker\Zed\Ratepay\Business\Api\Model\Parts\ShoppingBasketItem;
use Spryker\Zed\Ratepay\Business\Api\Model\Payment\Cancel as PaymentCancel;
use Spryker\Zed\Ratepay\Business\Api\Model\Payment\Confirm as PaymentConfirm;
use Spryker\Zed\Ratepay\Business\Api\Model\Payment\Init as PaymentInit;
use Spryker\Zed\Ratepay\Business\Api\Model\Payment\Refund as PaymentRefund;
use Spryker\Zed\Ratepay\Business\Api\Model\Payment\Request as PaymentRequest;
use Spryker\Zed\Ratepay\Business\Api\Model\RequestModelFactory;

/**
 * @method \Spryker\Zed\Ratepay\Persistence\RatepayQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Ratepay\RatepayConfig getConfig()
 */
class ApiFactory extends AbstractBusinessFactory
{

    /**
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\RequestModelFactoryInterface
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
                    ApiConstants::REQUEST_MODEL_BASKET_ITEM,
                    function () {
                        return $this->createBasketItemModel();
                    }
                )
                ->registerBuilder(
                    ApiConstants::REQUEST_MODEL_CUSTOMER,
                    function () use ($factory) {
                        return $this->createCustomerModel($factory);
                    }
                )
                ->registerBuilder(
                    ApiConstants::REQUEST_MODEL_ADDRESS,
                    function () {
                        return $this->createAddressModel();
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
                )
                ->registerBuilder(
                    ApiConstants::REQUEST_MODEL_BANK_ACCOUNT,
                    function () use ($factory) {
                        return $this->createBankAccountRequestModel();
                    }
                )
                ->registerBuilder(
                    ApiConstants::REQUEST_MODEL_PAYMENT_CONFIRM,
                    function () use ($factory) {
                        return $this->createPaymentConfirmModel($factory);
                    }
                )
                ->registerBuilder(
                    ApiConstants::REQUEST_MODEL_DELIVER_CONFIRM,
                    function () use ($factory) {
                        return $this->createDeliverConfirmModel($factory);
                    }
                )->registerBuilder(
                    ApiConstants::REQUEST_MODEL_PAYMENT_CANCEL,
                    function () use ($factory) {
                        return $this->cancelPayment($factory);
                    }
                )->registerBuilder(
                    ApiConstants::REQUEST_MODEL_PAYMENT_REFUND,
                    function () use ($factory) {
                        return $this->refundPayment($factory);
                    }
                );
        }

        return $factory;
    }

    /**
     * @param \Spryker\Zed\Ratepay\RatepayConfig $configuration
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Parts\Head
     */
    protected function createHeadModel($configuration)
    {
        return new Head(
            $configuration->getSystemId(),
            $configuration->getProfileId(),
            $configuration->getSecurityCode()
        );
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Parts\ShoppingBasket
     */
    protected function createBasketModel()
    {
        return new ShoppingBasket();
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Parts\ShoppingBasketItem
     */
    protected function createBasketItemModel()
    {
        return new ShoppingBasketItem();
    }

    /**
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\RequestModelFactory $factory
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Parts\Customer
     */
    protected function createCustomerModel(RequestModelFactory $factory)
    {
        return new Customer(
            $factory->build(ApiConstants::REQUEST_MODEL_ADDRESS),
            $factory->build(ApiConstants::REQUEST_MODEL_ADDRESS)
        );
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Parts\Address
     */
    protected function createAddressModel()
    {
        return new Address();
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Parts\Payment
     */
    protected function createPaymentModel()
    {
        return new Payment();
    }

    /**
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\RequestModelFactory $factory
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Init
     */
    protected function createInitModel(RequestModelFactory $factory)
    {
        return new PaymentInit($factory->build(ApiConstants::REQUEST_MODEL_HEAD));
    }

    /**
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\RequestModelFactory $factory
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Request
     */
    protected function createPaymentRequestModel(RequestModelFactory $factory)
    {
        return new PaymentRequest(
            $factory->build(ApiConstants::REQUEST_MODEL_HEAD),
            $factory->build(ApiConstants::REQUEST_MODEL_CUSTOMER),
            $factory->build(ApiConstants::REQUEST_MODEL_BASKET),
            $factory->build(ApiConstants::REQUEST_MODEL_PAYMENT)
        );
    }

    /**
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\RequestModelFactory $factory
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Confirm
     */
    protected function createPaymentConfirmModel(RequestModelFactory $factory)
    {
        return new PaymentConfirm($factory->build(ApiConstants::REQUEST_MODEL_HEAD));
    }

    /**
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\RequestModelFactory $factory
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Deliver\Confirm
     */
    protected function createDeliverConfirmModel(RequestModelFactory $factory)
    {
        return new DeliverConfirm(
            $factory->build(ApiConstants::REQUEST_MODEL_HEAD),
            $factory->build(ApiConstants::REQUEST_MODEL_BASKET)
        );
    }

    /**
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\RequestModelFactory $factory
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Cancel
     */
    protected function cancelPayment(RequestModelFactory $factory)
    {
        return new PaymentCancel(
            $factory->build(ApiConstants::REQUEST_MODEL_HEAD),
            $factory->build(ApiConstants::REQUEST_MODEL_BASKET)
        );
    }

    /**
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\RequestModelFactory $factory
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Refund
     */
    protected function refundPayment(RequestModelFactory $factory)
    {
        return new PaymentRefund(
            $factory->build(ApiConstants::REQUEST_MODEL_HEAD),
            $factory->build(ApiConstants::REQUEST_MODEL_BASKET)
        );
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Parts\BankAccount
     */
    protected function createBankAccountRequestModel()
    {
        return new BankAccount();
    }

}
