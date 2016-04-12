<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Ratepay\Business\Payment\MethodMapper;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\RatepayPaymentInvoiceTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\TotalsTransfer;

use Spryker\Shared\Ratepay\RatepayConstants;
use Spryker\Zed\Ratepay\Business\Api\Constants as ApiConstants;

use Spryker\Zed\Ratepay\Business\Api\ApiFactory;
use Spryker\Zed\Ratepay\Business\Api\Converter\Converter;
use Symfony\Component\HttpKernel\Log\NullLogger;
use Spryker\Zed\Ratepay\Business\Api\Adapter\Http\Guzzle;

use Orm\Zed\Ratepay\Persistence\Map\SpyPaymentRatepayTableMap;
use Spryker\Zed\Library\Generator\StringGenerator;
use Spryker\Zed\Ratepay\Business\Payment\Method\Invoice;

class InvoiceTest extends Test
{

    /**
     * @return void
     */
    public function testMapToPreCheck()
    {
        $quoteTransfer = $this->getQuoteTransfer();

        $invoiceMethod = new Invoice(
             $this->createAdapter(),
             $this->createApiRequestFactory(),
             $this->createMonolog(),
             $this->createConverter()
        );

        $invoiceMethod->paymentInit();

//        $requestData = $methodMapper->buildPreCheckRequest($quoteTransfer);

//        $this->assertSame(ApiConstants::BRAND_INVOICE, $requestData['ACCOUNT.BRAND']);
//        $this->assertSame(ApiConstants::PAYMENT_CODE_PRE_CHECK, $requestData['PAYMENT.CODE']);
//        $this->assertSame('Straße des 17. Juni 135', $requestData['ADDRESS.STREET']);
//        $this->assertSame(ApiConstants::CRITERION_PRE_CHECK, 'CRITERION.Ratepay_PRE_CHECK');
//        $this->assertSame('TRUE', $requestData['CRITERION.Ratepay_PRE_CHECK']);
    }

    /**
     * @param string $gatewayUrl
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Adapter\AdapterInterface
     */
    private function createAdapter()
    {
        return new Guzzle('https://gateway-int.ratepay.com/api/xml/1_0');
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Api\Converter\Converter
     */
    private function createConverter()
    {
        return new Converter();
    }

    /**
     * @return \Monolog\Logger
     */
    private function createMonolog()
    {
        return new NullLogger();
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\RequestModelFactoryInterface
     */
    private function createApiRequestFactory()
    {
        $factory = new ApiFactory();

        return $factory->createRequestModelFactory();
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    private function getQuoteTransfer()
    {

        $customerTransfer = new CustomerTransfer();
        $customerTransfer
            ->setEmail('test@test.com')
            ->setFirstName('John')
            ->setLastName('Doe')
            ->setLastName('Doe')
            ->setSalutation('Mr')
            ->setCompany('CompanyTest');

        $totalsTransfer = new TotalsTransfer();
        $totalsTransfer
            ->setGrandTotal(3346)
            ->setSubtotal(2856)
            ->setDiscountTotal(0)
            ->setExpenseTotal(490);

        $addressTransfer = new AddressTransfer();
        $addressTransfer
            ->setFirstName('John')
            ->setLastName('Doe')
            ->setCity('Berlin')
            ->setIso2Code('DE')
            ->setAddress1('Straße des 17. Juni')
            ->setAddress2('135')
            ->setAddress3('135')
            ->setZipCode('10623')
            ->setSalutation('Mr')
            ->setPhone('12345678');

        $paymentTransfer = new RatepayPaymentInvoiceTransfer();
        $paymentTransfer
            ->setResultCode(503)
            ->setDateOfBirth('11.11.1991')
            ->setCurrencyIso3('EUR')
            ->setCustomerAllowCreditInquiry(true)
            ->setGender('M')
            ->setIpAddress('127.0.0.1')
            ->setPaymentType('')
            ->setTransactionId('58-201604122719694')
            ->setTransactionShortId('5QTZ.2VWD.OMWW.9D3E');

        $payment = new PaymentTransfer();
        $payment->setRatepayInvoice($paymentTransfer);

        $quoteItemTransfer = new ItemTransfer();
        $quoteItemTransfer
            ->setSku('33333')
            ->setGroupKey('33333')
            ->setQuantity('2')
            ->setUnitGrossPrice('2222')
            ->setTaxRate('19')
            ->setUnitTotalDiscountAmountWithProductOption('19')
            ->setUnitGrossPriceWithProductOptionAndDiscountAmounts('55555');


        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->setBillingAddress($addressTransfer);
        $quoteTransfer->setPayment($payment);
        $quoteTransfer->setCustomer($customerTransfer);
        $quoteTransfer->addItem($quoteItemTransfer);

        return $quoteTransfer;
    }

//    /**
//     * @return void
//     */
//    public function testMapToPreAuthorization()
//    {
//        $methodMapper = new Invoice($this->getBundleConfigMock());
//        $paymentEntityMock = $this->getPaymentEntityMock();
//        $orderTransfer = $this->createOrderTransfer();
//        $requestData = $methodMapper->buildPreAuthorizationRequest($orderTransfer, $paymentEntityMock);
//
//        $this->assertSame($paymentEntityMock->getEmail(), $requestData['CONTACT.EMAIL']);
//        $this->assertSame(ApiConstants::BRAND_INVOICE, $requestData['ACCOUNT.BRAND']);
//        $this->assertSame(ApiConstants::PAYMENT_CODE_PRE_AUTHORIZATION, $requestData['PAYMENT.CODE']);
//    }
//
//    /**
//     * @return void
//     */
//    public function testMapToReAuthorization()
//    {
//        $uniqueId = $this->getRandomString();
//        $methodMapper = new Invoice($this->getBundleConfigMock());
//        $paymentEntityMock = $this->getPaymentEntityMock();
//        $orderTransfer = $this->createOrderTransfer();
//        $requestData = $methodMapper->buildReAuthorizationRequest($orderTransfer, $paymentEntityMock, $uniqueId);
//
//        $this->assertSame(ApiConstants::BRAND_INVOICE, $requestData['ACCOUNT.BRAND']);
//        $this->assertSame(ApiConstants::PAYMENT_CODE_RE_AUTHORIZATION, $requestData['PAYMENT.CODE']);
//        $this->assertSame($uniqueId, $requestData['IDENTIFICATION.REFERENCEID']);
//    }

    /**
     * @return string
     */
    private function getRandomString()
    {
        $generator = new StringGenerator();

        return 'test_' . $generator->generateRandomString();
    }

//    /**
//     * @return void
//     */
//    public function testMapToReversal()
//    {
//        $uniqueId = $this->getRandomString();
//        $methodMapper = new Invoice($this->getBundleConfigMock());
//        $paymentEntityMock = $this->getPaymentEntityMock();
//        $orderTransfer = $this->createOrderTransfer();
//        $requestData = $methodMapper->buildRevertRequest($orderTransfer, $paymentEntityMock, $uniqueId);
//
//        $this->assertSame(ApiConstants::BRAND_INVOICE, $requestData['ACCOUNT.BRAND']);
//        $this->assertSame(ApiConstants::PAYMENT_CODE_REVERSAL, $requestData['PAYMENT.CODE']);
//        $this->assertSame($uniqueId, $requestData['IDENTIFICATION.REFERENCEID']);
//    }

//    /**
//     * @return void
//     */
//    public function testMapToCapture()
//    {
//        $uniqueId = $this->getRandomString();
//        $methodMapper = new Invoice($this->getBundleConfigMock());
//        $paymentEntityMock = $this->getPaymentEntityMock();
//        $orderTransfer = $this->createOrderTransfer();
//        $requestData = $methodMapper->buildCaptureRequest($orderTransfer, $paymentEntityMock, $uniqueId);
//
//        $this->assertSame(ApiConstants::BRAND_INVOICE, $requestData['ACCOUNT.BRAND']);
//        $this->assertSame(ApiConstants::PAYMENT_CODE_CAPTURE, $requestData['PAYMENT.CODE']);
//        $this->assertSame($uniqueId, $requestData['IDENTIFICATION.REFERENCEID']);
//    }

//    /**
//     * @return void
//     */
//    public function testMapToRefund()
//    {
//        $uniqueId = $this->getRandomString();
//        $methodMapper = new Invoice($this->getBundleConfigMock());
//        $paymentEntityMock = $this->getPaymentEntityMock();
//        $orderTransfer = $this->createOrderTransfer();
//        $requestData = $methodMapper->buildRefundRequest($orderTransfer, $paymentEntityMock, $uniqueId);
//
//        $this->assertSame(ApiConstants::BRAND_INVOICE, $requestData['ACCOUNT.BRAND']);
//        $this->assertSame(ApiConstants::PAYMENT_CODE_REFUND, $requestData['PAYMENT.CODE']);
//        $this->assertSame($uniqueId, $requestData['IDENTIFICATION.REFERENCEID']);
//    }

    /**
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function createOrderTransfer()
    {
        $orderTransfer = new OrderTransfer();
        $totalTransfer = new TotalsTransfer();
        $totalTransfer->setGrandTotal(1000);
        $orderTransfer->setTotals($totalTransfer);

        return $orderTransfer;
    }

//    /**
//     * @return \Orm\Zed\Ratepay\Persistence\SpyPaymentRatepay
//     */
//    private function getPaymentEntityMock()
//    {
//        $orderEntityMock = $this->getMock(
//            'Orm\Zed\Sales\Persistence\SpySalesOrder',
//            []
//        );
//
//        /** @var \Orm\Zed\Ratepay\Persistence\SpyPaymentRatepay|\PHPUnit_Framework_MockObject_MockObject $paymentEntityMock*/
//        $paymentEntityMock = $this->getMock(
//            'Orm\Zed\Ratepay\Persistence\SpyPaymentRatepay',
//            [
//                'getSpySalesOrder',
//            ]
//        );
//        $paymentEntityMock
//            ->expects($this->any())
//            ->method('getSpySalesOrder')
//            ->will($this->returnValue($orderEntityMock));
//
//        $paymentEntityMock
//            ->setIdPaymentRatepay(1)
//            ->setClientIp('127.0.0.1')
//            ->setAccountBrand(ApiConstants::BRAND_INVOICE)
//            ->setFirstName('John')
//            ->setLastName('Doe')
//            ->setEmail('john@doe.com')
//            ->setSalutation('Mr')
//            ->setDateOfBirth('1970-01-01')
//            ->setCountryIso2Code('DE')
//            ->setCity('Berlin')
//            ->setStreet('Straße des 17. Juni 135')
//            ->setZipCode('10623')
//            ->setGender(SpyPaymentRatepayTableMap::COL_GENDER_FEMALE);
//
//        return $paymentEntityMock;
//    }

}
