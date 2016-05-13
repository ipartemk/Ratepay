<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Api\Model\Payment;

use Spryker\Zed\Ratepay\Business\Api\Constants;
use Spryker\Zed\Ratepay\Business\Api\Model\Base;
use Spryker\Zed\Ratepay\Business\Api\Model\Builder\Customer;
use Spryker\Zed\Ratepay\Business\Api\Model\Builder\Head;
use Spryker\Zed\Ratepay\Business\Api\Model\Builder\Payment;
use Spryker\Zed\Ratepay\Business\Api\Model\Builder\ShoppingBasket;

class Request extends Base
{

    const OPERATION = Constants::REQUEST_MODEL_PAYMENT_REQUEST;

    /**
     * @var \Spryker\Zed\Ratepay\Business\Api\Model\Builder\Customer
     */
    protected $customer;

    /**
     * @var \Spryker\Zed\Ratepay\Business\Api\Model\Builder\ShoppingBasket
     */
    protected $basket;

    /**
     * @var \Spryker\Zed\Ratepay\Business\Api\Model\Builder\Payment
     */
    protected $payment;

    /**
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Builder\Head $head
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Builder\Customer $customer
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Builder\ShoppingBasket $basket
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Builder\Payment $payment
     */
    public function __construct(
        Head $head,
        Customer $customer,
        ShoppingBasket $basket,
        Payment $payment
    ) {

        parent::__construct($head);
        $this->customer = $customer;
        $this->basket = $basket;
        $this->payment = $payment;
    }

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
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Builder\Customer $customer
     *
     * @return $this
     */
    public function setCustomer(Customer $customer)
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Builder\Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Builder\ShoppingBasket $basket
     *
     * @return $this
     */
    public function setShoppingBasket(ShoppingBasket $basket)
    {
        $this->basket = $basket;

        return $this;
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Builder\ShoppingBasket
     */
    public function getShoppingBasket()
    {
        return $this->basket;
    }

    /**
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Builder\Payment $payment
     *
     * @return $this
     */
    public function setPayment(Payment $payment)
    {
        $this->payment = $payment;

        return $this;
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Builder\Payment
     */
    public function getPayment()
    {
        return $this->payment;
    }

}
