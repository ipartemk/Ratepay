<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Api\Model\Payment;

use Spryker\Zed\Ratepay\Business\Api\Model\Parts\Customer;
use Spryker\Zed\Ratepay\Business\Api\Model\Parts\Payment;
use Spryker\Zed\Ratepay\Business\Api\Model\Parts\ShoppingBasket;

class Request extends Base
{

    const OPERATION = 'PAYMENT_REQUEST';

    /**
     * @var \Spryker\Zed\Ratepay\Business\Api\Model\Parts\Customer
     */
    protected $customer;

    /**
     * @var \Spryker\Zed\Ratepay\Business\Api\Model\Parts\ShoppingBasket
     */
    protected $basket;

    /**
     * @var \Spryker\Zed\Ratepay\Business\Api\Model\Parts\Payment
     */
    protected $payment;

    /**
     * @return array
     */
    protected function buildData()
    {
        $result = parent::buildData();
        $result['content'] = [
            $this->getPayment()->getRootTag()=> $this->getPayment(),
            $this->getCustomer()->getRootTag() => $this->getCustomer(),
            $this->getShoppingBasket()->getRootTag() => $this->getShoppingBasket(),
        ];
        return $result;
    }

    /**
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Parts\Customer $customer
     *
     * @return $this
     */
    public function setCustomer(Customer $customer)
    {
        $this->customer = $customer;
        return $this;
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Parts\Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Parts\ShoppingBasket $basket
     *
     * @return $this
     */
    public function setShoppingBasket(ShoppingBasket $basket)
    {
        $this->basket = $basket;
        return $this;
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Parts\ShoppingBasket
     */
    public function getShoppingBasket()
    {
        return $this->basket;
    }

    /**
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Parts\Payment $payment
     *
     * @return $this
     */
    public function setPayment(Payment $payment)
    {
        $this->payment = $payment;
        return $this;
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Parts\Payment
     */
    public function getPayment()
    {
        return $this->payment;
    }

}
