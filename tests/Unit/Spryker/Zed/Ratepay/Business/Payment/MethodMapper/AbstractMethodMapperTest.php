<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Ratepay\Business\Payment\MethodMapper;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

use Generated\Shared\Transfer\TotalsTransfer;

use Spryker\Zed\Ratepay\Business\Api\ApiFactory;

use Spryker\Zed\Ratepay\Business\Api\Converter\Converter;

abstract class AbstractMethodMapperTest extends Test
{

    /**
     * @return \Spryker\Zed\Ratepay\Business\Api\Converter\Converter
     */
    protected function createConverter()
    {
        return new Converter();
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\RequestModelFactoryInterface
     */
    protected function createApiRequestFactory()
    {
        $factory = new ApiFactory();

        return $factory->createRequestModelFactory();
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function getQuoteTransfer()
    {
        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->setTotals($this->getTotalsTransfer())
            ->setBillingAddress($this->getAddressTransfer('billing'))
            ->setShippingAddress($this->getAddressTransfer('shipping'))
            ->setCustomer($this->getCustomerTransfer())
            ->setPayment($this->getPaymentTransfer())
            ->addItem($this->getItemTransfer(1))
            ->addItem($this->getItemTransfer(2));

        return $quoteTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function getOrderTransfer()
    {
        $orderTransfer = new OrderTransfer();
        $orderTransfer->setTotals($this->getTotalsTransfer())
            ->setBillingAddress($this->getAddressTransfer('billing'))
            ->setShippingAddress($this->getAddressTransfer('shipping'))
            ->setCustomer($this->getCustomerTransfer())
            ->addItem($this->getItemTransfer(1))
            ->addItem($this->getItemTransfer(2));

        return $orderTransfer;
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Payment\Method\MethodInterface
     */
    abstract public function getPaymentMethod();

    /**
     * @return \Generated\Shared\Transfer\PaymentTransfer
     */
    protected  abstract function getPaymentTransfer();

    /**
     * @return void
     */
    public function testPaymentInit()
    {
        $paymentMethod = $this->getPaymentMethod();
        $request = $paymentMethod->paymentInit();

        $this->assertInstanceOf('\Spryker\Zed\Ratepay\Business\Api\Model\Payment\Init', $request);
    }


    /**
     * @return void
     */
    public function testPaymentRequest()
    {
        $paymentMethod = $this->getPaymentMethod();
        $quoteTransfer = $this->getQuoteTransfer();

        $request = $paymentMethod->paymentRequest($quoteTransfer, 'test1', 'test2', 305);

        $this->assertInstanceOf('\Spryker\Zed\Ratepay\Business\Api\Model\Payment\Request', $request);

    }

//
//    /**
//     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
//     *
//     * @return void
//     */
//    abstract public function testPaymentConfirm(OrderTransfer $orderTransfer);
//
//    /**
//     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
//     *
//     * @return void
//     */
//    abstract public function testDeliveryConfirm(OrderTransfer $orderTransfer);
//
//    /**
//     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
//     *
//     * @return void
//     */
//    abstract public function testPaymentCancel(OrderTransfer $orderTransfer);
//
//    /**
//     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
//     *
//     * @return void
//     */
//    abstract public function testPaymentRefund(OrderTransfer $orderTransfer);
//
//    /**
//     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
//     *
//     * @return void
//     */
//    abstract public function testPaymentChange(OrderTransfer $orderTransfer);

    protected function getCustomerTransfer()
    {
        $customerTransfer = new CustomerTransfer();
        $customerTransfer
            ->setEmail('test@test.com')
            ->setFirstName('John')
            ->setLastName('Doe')
            ->setLastName('Doe')
            ->setSalutation('Mr')
            ->setCompany('CompanyTest');

        return $customerTransfer;
    }

    protected function getTotalsTransfer()
    {
        $totalsTransfer = new TotalsTransfer();
        $totalsTransfer
            ->setGrandTotal(3346)
            ->setSubtotal(2856)
            ->setDiscountTotal(0)
            ->setExpenseTotal(490);

        return $totalsTransfer;
    }

    protected function getAddressTransfer($itemPrefix)
    {
        $addressTransfer = new AddressTransfer();
        $addressTransfer
            ->setFirstName($itemPrefix . 'John')
            ->setLastName($itemPrefix . 'Doe')
            ->setCity('Berlin')
            ->setIso2Code('DE')
            ->setAddress1($itemPrefix . 'Straße des 17. Juni')
            ->setAddress2($itemPrefix . '135')
            ->setAddress3($itemPrefix . '135')
            ->setZipCode($itemPrefix . '10623')
            ->setSalutation('Mr')
            ->setPhone($itemPrefix . '12345678');

        return $addressTransfer;
    }

    protected function getItemTransfer($itemPrefix)
    {
        $itemTransfer = new ItemTransfer();
        $itemTransfer
            ->setName($itemPrefix . 'test')
            ->setSku($itemPrefix . '33333')
            ->setGroupKey($itemPrefix . '33333')
            ->setQuantity('2')
            ->setUnitGrossPrice('2222')
            ->setTaxRate('19')
            ->setUnitTotalDiscountAmountWithProductOption('19')
            ->setUnitGrossPriceWithProductOptions('55555');

        return $itemTransfer;
    }

}
