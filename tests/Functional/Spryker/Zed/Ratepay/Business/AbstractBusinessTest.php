<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\Ratepay\Business;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Orm\Zed\Country\Persistence\SpyCountryQuery;
use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;
use Orm\Zed\Customer\Persistence\SpyCustomer;
use Orm\Zed\Oms\Persistence\SpyOmsOrderItemState;
use Orm\Zed\Oms\Persistence\SpyOmsOrderProcess;
use Orm\Zed\Ratepay\Persistence\SpyPaymentRatepayQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderAddress;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemBundle;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemBundleItem;
use Spryker\Zed\Ratepay\Business\Order\Saver;
use Spryker\Zed\Ratepay\Business\RatepayBusinessFactory;

abstract class AbstractBusinessTest extends Test
{

    const PAYMENT_METHOD = '';

    /**
     * @var \Orm\Zed\Ratepay\Persistence\SpyPaymentRatepay
     */
    protected $paymentEntity;

    /**
     * @var \Generated\Shared\Transfer\QuoteTransfer
     */
    protected $quoteTransfer;

    /**
     * @var \Generated\Shared\Transfer\OrderTransfer
     */
    protected $orderTransfer;

    /**
     * @var \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    protected $checkoutResponseTransfer;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->checkoutResponseTransfer = $this->createCheckoutResponse();
        $this->quoteTransfer = $this->getQuoteTransfer();
        $this->orderTransfer = $this->getOrderTransfer();

        $orderEntity = $this->createOrderEntity();
        $this->checkoutResponseTransfer->getSaveOrder()->setIdSalesOrder($orderEntity->getIdSalesOrder());

        $paymentMapper = $this->getPaymentMapper();
        $orderManager = new Saver($this->quoteTransfer, $this->checkoutResponseTransfer, $paymentMapper);
        $orderManager->saveOrderPayment();

        $this->paymentEntity = SpyPaymentRatepayQuery::create()->findOneByFkSalesOrder(
            $this->checkoutResponseTransfer->getSaveOrder()->getIdSalesOrder()
        );
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function getQuoteTransfer()
    {
        $quoteTransfer = (new QuoteTransfer())
            ->setTotals($this->getTotalsTransfer())
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
        $orderTransfer = (new OrderTransfer())
            ->setOrderReference('TEST--1')
            ->setTotals($this->getTotalsTransfer())
            ->setBillingAddress($this->getAddressTransfer('billing'))
            ->setShippingAddress($this->getAddressTransfer('shipping'))
            ->setCustomer($this->getCustomerTransfer())
            ->addItem($this->getItemTransfer(1))
            ->addItem($this->getItemTransfer(2));

        return $orderTransfer;
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
            ->setGroupKey($itemPrefix . '33333333333')
            ->setQuantity($itemPrefix . '2')
            ->setUnitGrossPrice($itemPrefix . '1')
            ->setTaxRate($itemPrefix . '9')
            ->setUnitTotalDiscountAmountWithProductOption($itemPrefix . '9')
            ->setUnitGrossPriceWithProductOptions($itemPrefix . '55555');

        return $itemTransfer;
    }

    protected function getCustomerTransfer()
    {
        $customerTransfer = new CustomerTransfer();
        $customerTransfer
            ->setEmail('test@test.com')
            ->setFirstName('John')
            ->setPhone('123-123-123')
            ->setCompany('company test')
            ->setCustomerReference('ratepay-pre-authorization-test')
            ->setDateOfBirth('1991-11-11')
            ->setLastName('Doe');

        return $customerTransfer;
    }

    /**
     *
     * @return \Generated\Shared\Transfer\PaymentTransfer
     */
    protected function getPaymentTransfer()
    {
        $paymentTransfer = $this->getRatepayPaymentMethodTransfer()
            ->setResultCode(503)
            ->setDateOfBirth('11.11.1991')
            ->setCurrencyIso3('EUR')
            ->setCustomerAllowCreditInquiry(true)
            ->setGender('M')
            ->setPhone('123456789')
            ->setIpAddress('127.0.0.1')
            ->setPaymentType(static::PAYMENT_METHOD)
            ->setTransactionId('58-201604122719694')
            ->setTransactionShortId('5QTZ.2VWD.OMWW.9D3E')
            ->setDeviceFingerprint('122356');

        $payment = new PaymentTransfer();
        $this->setRatepayPaymentDataToPaymentTransfer($payment, $paymentTransfer);
        $payment->setPaymentMethod(static::PAYMENT_METHOD);

        return $payment;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentTransfer $payment
     * @param \Spryker\Shared\Transfer\TransferInterface $paymentTransfer
     *
     * @return void
     */
    abstract protected function setRatepayPaymentDataToPaymentTransfer($payment, $paymentTransfer);

    /**
     * @return \Spryker\Shared\Transfer\TransferInterface
     */
    abstract protected function getRatepayPaymentMethodTransfer();


    /**
     * @return mixed
     */
    abstract protected function getPaymentTransferFromQuote();

    protected function getPaymentMapper()
    {
        return $this->getRatepayBusinessBusinessFactory()
            ->getMethodMapperFactory()
            ->createPaymentTransactionHandler()
            ->prepareMethodMapper($this->quoteTransfer);
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\RatepayBusinessFactory
     */
    protected function getRatepayBusinessBusinessFactory()
    {
        $businessFactory = new RatepayBusinessFactory();

        return $businessFactory;
    }

    /**
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    protected function createCheckoutResponse()
    {
        $checkoutResponseTransfer = new CheckoutResponseTransfer();
        $saveOrderTransfer = new SaveOrderTransfer();
        $checkoutResponseTransfer->setSaveOrder($saveOrderTransfer);

        return $checkoutResponseTransfer;
    }

    /**
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    protected function createOrderEntity()
    {
        $country = SpyCountryQuery::create()->findOneByIso2Code('DE');

        $billingAddress = (new SpySalesOrderAddress())
            ->setFkCountry($country->getIdCountry())
            ->setFirstName('John')
            ->setLastName('Doe')
            ->setAddress1('Straße des 17. Juni 135')
            ->setCity('Berlin')
            ->setZipCode('10623');
        $billingAddress->save();

        $customer = (new SpyCustomer())
            ->setFirstName('John')
            ->setLastName('Doe')
            ->setEmail('john@doe.com')
            ->setDateOfBirth('1970-01-01')
            ->setGender(SpyCustomerTableMap::COL_GENDER_MALE)
            ->setCustomerReference('payolution-pre-authorization-test');
        $customer->save();

        $orderEntity = (new SpySalesOrder())
            ->setEmail('john@doe.com')
            ->setIsTest(true)
            ->setFkSalesOrderAddressBilling($billingAddress->getIdSalesOrderAddress())
            ->setFkSalesOrderAddressShipping($billingAddress->getIdSalesOrderAddress())
            ->setCustomer($customer)
            ->setOrderReference('foo-bar-baz-2');
        $orderEntity->save();

        $this->createOrderItemEntity($orderEntity->getIdSalesOrder());

        return $orderEntity;
    }


    /**
     * @param int $idSalesOrder
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItem
     */
    protected function createOrderItemEntity($idSalesOrder)
    {
        $stateEntity = $this->createOrderItemStateEntity();
        $processEntity = $this->createOrderProcessEntity();
        $bundleEntity = $this->createOrderItemBundleEntity();

        $orderItemEntity = new SpySalesOrderItem();
        $orderItemEntity
            ->setFkSalesOrder($idSalesOrder)
            ->setFkOmsOrderItemState($stateEntity->getIdOmsOrderItemState())
            ->setFkOmsOrderProcess($processEntity->getIdOmsOrderProcess())
            ->setFkSalesOrderItemBundle($bundleEntity->getIdSalesOrderItemBundle())
            ->setName('test product')
            ->setSku('1324354657687980')
            ->setGrossPrice(1000)
            ->setQuantity(1);
        $orderItemEntity->save();

        return $orderItemEntity;
    }

    /**
     * @return \Orm\Zed\Oms\Persistence\SpyOmsOrderItemState
     */
    protected function createOrderItemStateEntity()
    {
        $stateEntity = new SpyOmsOrderItemState();
        $stateEntity->setName('test item state');
        $stateEntity->save();

        return $stateEntity;
    }

    /**
     * @return \Orm\Zed\Oms\Persistence\SpyOmsOrderProcess
     */
    protected function createOrderProcessEntity()
    {
        $processEntity = new SpyOmsOrderProcess();
        $processEntity->setName('test process');
        $processEntity->save();

        return $processEntity;
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemBundle
     */
    protected function createOrderItemBundleEntity()
    {
        $bundleEntity = new SpySalesOrderItemBundle();
        $bundleEntity
            ->setName('test bundle')
            ->setSku('13243546')
            ->setGrossPrice(1000)
            ->setBundleType('NonSplitBundle');
        $bundleEntity->save();

        $bundleItemEntity = new SpySalesOrderItemBundleItem();
        $bundleItemEntity
            ->setFkSalesOrderItemBundle($bundleEntity->getIdSalesOrderItemBundle())
            ->setName('test bundle item')
            ->setSku('13243546')
            ->setGrossPrice(1000)
            ->setVariety('Simple');
        $bundleItemEntity->save();

        return $bundleEntity;
    }

}
