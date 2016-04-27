<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Ratepay\Business\Payment\MethodMapper;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Orm\Zed\Ratepay\Persistence\SpyPaymentRatepay;
use Orm\Zed\Ratepay\Persistence\SpyPaymentRatepayQuery;
use Spryker\Zed\Ratepay\Business\Api\ApiFactory;
use Spryker\Zed\Ratepay\Business\Api\Mapper\MapperFactory;
use Spryker\Zed\Ratepay\Persistence\RatepayQueryContainerInterface;
use Unit\Spryker\Zed\Ratepay\Business\Payment\BasePaymentTest;

abstract class AbstractMethodMapperTest extends BasePaymentTest
{

    /**
     * @return \Spryker\Zed\Ratepay\Business\Api\Mapper\MapperFactory
     */
    protected function createMapperFactory()
    {
        return new MapperFactory();
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
        $orderTransfer
            ->setIdSalesOrder('TEST--1')
            ->setOrderReference('TEST--1')
            ->setTotals($this->getTotalsTransfer())
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

        $this->assertEquals('Spryker www.spryker.dev', $request->getHead()->getSystemId());

        $this->assertNotNull($request->getHead()->getProfileId());
        $this->assertNotNull($request->getHead()->getSecurityCode());

        $this->assertNull($request->getHead()->getOperation());
        $this->assertNull($request->getHead()->getTransactionId());
        $this->assertNull($request->getHead()->getTransactionShortId());
        $this->assertNull($request->getHead()->getExternalOrderId());
        $this->assertNull($request->getHead()->getOperationSubstring());
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
        $this->assertEquals('Spryker www.spryker.dev', $request->getHead()->getSystemId());

        //head
        $this->assertNotNull($request->getHead()->getProfileId());
        $this->assertNotNull($request->getHead()->getSecurityCode());

        //customer data
        $this->assertEquals('test@test.com', $request->getCustomer()->getEmail());
        $this->assertEquals('billingJohn', $request->getCustomer()->getFirstName());
        $this->assertEquals('billingDoe', $request->getCustomer()->getLastName());
        $this->assertEquals('M', $request->getCustomer()->getGender());
        $this->assertEquals('yes', $request->getCustomer()->getAllowCreditInquiry());
        $this->assertEquals('billing12345678', $request->getCustomer()->getPhone());
        $this->assertNotNull($request->getCustomer()->getIpAddress());

        //basket and items
        $this->testBasketAndItems($request);

        //payment
        $this->assertEquals('EUR', $request->getPayment()->getCurrency());
        $this->assertEquals('33.46', $request->getPayment()->getAmount());
        $this->testPaymentSpecificRequestData($request);
    }

    /**
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Request $request
     *
     * @return void
     */
    abstract protected function testPaymentSpecificRequestData($request);


    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function testPaymentConfirm()
    {
        $paymentMethod = $this->getPaymentMethod();
        $request = $paymentMethod->paymentConfirm($this->getOrderTransfer());

        $this->assertInstanceOf('\Spryker\Zed\Ratepay\Business\Api\Model\Payment\Confirm', $request);

        //head
        $this->assertNotNull($request->getHead()->getProfileId());
        $this->assertNotNull($request->getHead()->getSecurityCode());

        $this->assertEquals('TEST--1', $request->getHead()->getExternalOrderId());
        $this->assertEquals('Spryker www.spryker.dev', $request->getHead()->getSystemId());
    }

    /**
     * @return void
     */
    public function testDeliveryConfirm()
    {
        $paymentMethod = $this->getPaymentMethod();
        $request = $paymentMethod->deliveryConfirm($this->getOrderTransfer());

        $this->assertInstanceOf('\Spryker\Zed\Ratepay\Business\Api\Model\Deliver\Confirm', $request);

        //head
        $this->assertNotNull($request->getHead()->getProfileId());
        $this->assertNotNull($request->getHead()->getSecurityCode());

        $this->assertEquals('58-201604122719694', $request->getHead()->getTransactionId());
        $this->assertEquals('5QTZ.2VWD.OMWW.9D3E', $request->getHead()->getTransactionShortId());

        $this->assertEquals('Spryker www.spryker.dev', $request->getHead()->getSystemId());

        //basket and items
        $this->testBasketAndItems($request);

    }

    /**
     * @return void
     */
    public function testPaymentCancel()
    {
        $paymentMethod = $this->getPaymentMethod();
        $request = $paymentMethod->paymentCancel($this->getOrderTransfer());

        $this->assertInstanceOf('\Spryker\Zed\Ratepay\Business\Api\Model\Payment\Cancel', $request);

        //head
        $this->assertNotNull($request->getHead()->getProfileId());
        $this->assertNotNull($request->getHead()->getSecurityCode());

        $this->assertEquals('58-201604122719694', $request->getHead()->getTransactionId());
        $this->assertEquals('5QTZ.2VWD.OMWW.9D3E', $request->getHead()->getTransactionShortId());

        $this->assertEquals('TEST--1', $request->getHead()->getExternalOrderId());
        $this->assertEquals('Spryker www.spryker.dev', $request->getHead()->getSystemId());

        //basket and items
        $this->testBasketAndItems($request);
    }

    /**
     * @return void
     */
    public function testPaymentRefund()
    {
        $paymentMethod = $this->getPaymentMethod();
        $request = $paymentMethod->paymentRefund($this->getOrderTransfer());

        $this->assertInstanceOf('\Spryker\Zed\Ratepay\Business\Api\Model\Payment\Refund', $request);

        //head
        $this->assertNotNull($request->getHead()->getProfileId());
        $this->assertNotNull($request->getHead()->getSecurityCode());

        $this->assertEquals('58-201604122719694', $request->getHead()->getTransactionId());
        $this->assertEquals('5QTZ.2VWD.OMWW.9D3E', $request->getHead()->getTransactionShortId());

        $this->assertEquals('TEST--1', $request->getHead()->getExternalOrderId());
        $this->assertEquals('Spryker www.spryker.dev', $request->getHead()->getSystemId());

        //basket and items
        $this->testBasketAndItems($request);
    }

    protected function getCustomerTransfer()
    {
        $customerTransfer = new CustomerTransfer();
        $customerTransfer
            ->setEmail('test@test.com')
            ->setFirstName('John')
            ->setLastName('Doe');

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
            ->setGroupKey($itemPrefix . '33333333333')
            ->setQuantity($itemPrefix . '2')
            ->setUnitGrossPrice($itemPrefix . '1')
            ->setTaxRate($itemPrefix . '9')
            ->setUnitTotalDiscountAmountWithProductOption($itemPrefix . '9')
            ->setUnitGrossPriceWithProductOptions($itemPrefix . '55555');

        return $itemTransfer;
    }

    /**
     * @return \Spryker\Zed\Ratepay\Persistence\RatepayQueryContainerInterface
     */
    protected function getQueryContainerMock()
    {
        $queryContainer = $this->getMock(RatepayQueryContainerInterface::class);
        $queryPaymentsMock = $this->getMock(SpyPaymentRatepayQuery::class, ['findByFkSalesOrder', 'getFirst']);

        $ratepayPaymentEntity = new SpyPaymentRatepay();
        $this->setRatepayPaymentEntityData($ratepayPaymentEntity);

        $queryPaymentsMock->method('findByFkSalesOrder')->willReturnSelf();
        $queryPaymentsMock->method('getFirst')->willReturn($ratepayPaymentEntity);
        $queryContainer->method('queryPayments')->willReturn($queryPaymentsMock);

        return $queryContainer;
    }

    /**
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Request $request
     * @return void
     */
    protected function testBasketAndItems($request)
    {
        //Basket
        $this->assertEquals('33.46', $request->getShoppingBasket()->getAmount());
        $this->assertEquals('EUR', $request->getShoppingBasket()->getCurrency());
        $this->assertEquals('4.90', $request->getShoppingBasket()->getShippingUnitPrice());
        $this->assertEquals('0.00', $request->getShoppingBasket()->getShippingTaxRate());
        $this->assertEquals('Shipping costs', $request->getShoppingBasket()->getShippingTitle());
        $this->assertEquals('0.00', $request->getShoppingBasket()->getDiscountTaxRate());
        $this->assertEquals('0.00', $request->getShoppingBasket()->getDiscountUnitPrice());
        $this->assertEquals('Discount', $request->getShoppingBasket()->getDiscountTitle());

        $this->assertArrayHasKey(0, $request->getShoppingBasket()->getItems());
        $this->assertArrayHasKey(1, $request->getShoppingBasket()->getItems());

        //basketItems
        $basketItems = $request->getShoppingBasket()->getItems();

        /**
         * @var \Spryker\Zed\Ratepay\Business\Api\Model\Parts\ShoppingBasketItem $firstItem
         */
        $firstItem = $basketItems[0];
        $this->assertEquals('1test', $firstItem->getItemName());
        $this->assertEquals('133333', $firstItem->getArticleNumber());
        $this->assertEquals('133333333333', $firstItem->getUniqueArticleNumber());
        $this->assertEquals('12', $firstItem->getQuantity());
        $this->assertEquals('1555.55', $firstItem->getUnitPriceGross());
        $this->assertEquals('19', $firstItem->getTaxRate());
        $this->assertEquals(0, $firstItem->getDiscount());

        /**
         * @var \Spryker\Zed\Ratepay\Business\Api\Model\Parts\ShoppingBasketItem $secondItem
         */
        $secondItem = $basketItems[1];
        $this->assertEquals('2test', $secondItem->getItemName());
        $this->assertEquals('233333', $secondItem->getArticleNumber());
        $this->assertEquals('233333333333', $secondItem->getUniqueArticleNumber());
        $this->assertEquals('22', $secondItem->getQuantity());
        $this->assertEquals('2555.55', $secondItem->getUnitPriceGross());
        $this->assertEquals('29', $secondItem->getTaxRate());
        $this->assertEquals(0, $secondItem->getDiscount());
    }

    /**
     *
     * @param \Orm\Zed\Ratepay\Persistence\SpyPaymentRatepay $ratepayPaymentEntity
     *
     * @return void
     */
    abstract protected function setRatepayPaymentEntityData($ratepayPaymentEntity);

}
