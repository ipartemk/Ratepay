<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Api\Model\Builder;

use Generated\Shared\Transfer\RatepayRequestTransfer;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\Ratepay\Persistence\RatepayQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Ratepay\RatepayConfig getConfig()
 */
class BuilderFactory extends AbstractBusinessFactory
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

    /**
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Builder\Customer
     */
    public function createCustomer()
    {
        return new Customer(
            $this->requestTransfer
        );
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Builder\Address
     */
    public function createCustomerAddress()
    {
        return new Address(
            $this->requestTransfer
        );
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Builder\BankAccount
     */
    public function createBankAccount()
    {
        return new BankAccount(
            $this->requestTransfer
        );
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Builder\Head
     */
    public function createHead()
    {
        $this->requestTransfer->getHead()
            ->setSystemId($this->getConfig()->getSystemId())
            ->setProfileId($this->getConfig()->getProfileId())
            ->setSecurityCode($this->getConfig()->getSecurityCode());

        return new Head(
            $this->requestTransfer
        );
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Builder\Payment
     */
    public function createPayment()
    {
        return new Payment(
            $this->requestTransfer
        );
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Builder\ShoppingBasket
     */
    public function createShoppingBasket()
    {
        return new ShoppingBasket(
            $this->requestTransfer
        );
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Builder\ShoppingBasketItem
     */
    public function createShoppingBasketItem()
    {
        return new ShoppingBasketItem(
            $this->requestTransfer
        );
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Builder\InstallmentCalculation
     */
    public function createInstallmentCalculation()
    {
        return new InstallmentCalculation(
            $this->requestTransfer
        );
    }

}
