<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\Ratepay\Business;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Orm\Zed\Country\Persistence\SpyCountryQuery;
use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;
use Orm\Zed\Customer\Persistence\SpyCustomerQuery;
use Orm\Zed\Ratepay\Persistence\Map\SpyPaymentRatepayTableMap;
use Orm\Zed\Ratepay\Persistence\SpyPaymentRatepay;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderAddress;
use Spryker\Shared\Ratepay\RatepayConstants;
use Spryker\Zed\Ratepay\Business\Api\Adapter\AdapterInterface;
use Spryker\Zed\Ratepay\Business\Api\Converter\Converter as ResponseConverter;

class AbstractFacadeTest extends Test
{

    /**
     * @var \Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    private $orderEntity;

    /**
     * @var \Orm\Zed\Ratepay\Persistence\SpyPaymentRatepay
     */
    private $paymentEntity;

    /**
     * @var \Spryker\Zed\Ratepay\Business\Api\Converter\Converter
     */
    private $responseConverter;

    /**
     * @var \Orm\Zed\Ratepay\Persistence\SpyPaymentRatepayTransactionRequestLogQuery
     */
    private $requestLogQuery;

    /**
     * @var \Orm\Zed\Ratepay\Persistence\SpyPaymentRatepayTransactionStatusLogQuery
     */
    private $statusLogQuery;

    /**
     * @return void
     */
    protected function _before()
    {
        parent::_before();
        $this->setUpSalesOrderTestData();
        $this->setUpPaymentTestData();
        $this->responseConverter = new ResponseConverter();
        $this->requestLogQuery = new SpyPaymentRatepayTransactionRequestLogQuery();
        $this->statusLogQuery = new SpyPaymentRatepayTransactionStatusLogQuery();
    }

    /**
     * @return void
     */
    protected function setUpSalesOrderTestData()
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

        $customer = (new SpyCustomerQuery())
            ->filterByFirstName('John')
            ->filterByLastName('Doe')
            ->filterByEmail('john@doe.com')
            ->filterByDateOfBirth('1970-01-01')
            ->filterByGender(SpyCustomerTableMap::COL_GENDER_MALE)
            ->filterByCustomerReference('Ratepay-pre-authorization-test')
            ->findOneOrCreate();

        $customer->save();

        $this->orderEntity = (new SpySalesOrder())
            ->setEmail('john@doe.com')
            ->setIsTest(true)
            ->setFkSalesOrderAddressBilling($billingAddress->getIdSalesOrderAddress())
            ->setFkSalesOrderAddressShipping($billingAddress->getIdSalesOrderAddress())
            ->setCustomer($customer)
            ->setOrderReference('foo-bar-baz-2');

        $this->orderEntity->save();
    }

    /**
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function createOrderTransfer()
    {
        $orderTransfer = new OrderTransfer();
        $totalTransfer = new TotalsTransfer();
        $totalTransfer->setGrandTotal(1000);
        $orderTransfer->setTotals($totalTransfer);
        $orderTransfer->setIdSalesOrder($this->orderEntity->getIdSalesOrder());

        return $orderTransfer;
    }

    /**
     * @return void
     */
    private function setUpPaymentTestData()
    {
        $this->paymentEntity = (new SpyPaymentRatepay())
            ->setFkSalesOrder($this->getOrderEntity()->getIdSalesOrder())
            ->setAccountBrand(RatepayConstants::BRAND_INVOICE)
            ->setClientIp('127.0.0.1')
            ->setFirstName('Jane')
            ->setLastName('Doe')
            ->setDateOfBirth('1970-01-02')
            ->setEmail('jane@family-doe.org')
            ->setGender(SpyPaymentRatepayTableMap::COL_GENDER_MALE)
            ->setSalutation(SpyPaymentRatepayTableMap::COL_SALUTATION_MR)
            ->setCountryIso2Code('DE')
            ->setCity('Berlin')
            ->setStreet('Straße des 17. Juni 135')
            ->setZipCode('10623')
            ->setLanguageIso2Code('DE')
            ->setCurrencyIso3Code('EUR');
        $this->paymentEntity->save();
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    protected function getOrderEntity()
    {
        return $this->orderEntity;
    }

    /**
     * @return \Orm\Zed\Ratepay\Persistence\SpyPaymentRatepay
     */
    protected function getPaymentEntity()
    {
        return $this->paymentEntity;
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Api\Converter\Converter
     */
    protected function getResponseConverter()
    {
        return $this->responseConverter;
    }

    /**
     * @return \Orm\Zed\Ratepay\Persistence\SpyPaymentRatepayTransactionRequestLogQuery
     */
    protected function getRequestLogQuery()
    {
        return $this->requestLogQuery;
    }

    /**
     * @return \Orm\Zed\Ratepay\Persistence\SpyPaymentRatepayTransactionStatusLogQuery
     */
    protected function getStatusLogQuery()
    {
        return $this->statusLogQuery;
    }

    /**
     * @return \Orm\Zed\Ratepay\Persistence\SpyPaymentRatepayTransactionRequestLog[]|\Propel\Runtime\Collection\ObjectCollection
     */
    protected function getRequestLogCollectionForPayment()
    {
        return $this
            ->getRequestLogQuery()
            ->findByFkPaymentRatepay($this->getPaymentEntity()->getIdPaymentRatepay());
    }

    /**
     * @return \Orm\Zed\Ratepay\Persistence\SpyPaymentRatepayTransactionStatusLog[]|\Propel\Runtime\Collection\ObjectCollection
     */
    protected function getStatusLogCollectionForPayment()
    {
        return $this
            ->getStatusLogQuery()
            ->findByFkPaymentRatepay($this->getPaymentEntity()->getIdPaymentRatepay());
    }

    /**
     * @param \Spryker\Zed\Ratepay\Business\Api\Adapter\AdapterInterface $adapter
     *
     * @return \Spryker\Zed\Ratepay\Business\RatepayFacade
     */
    protected function getFacadeMock(AdapterInterface $adapter)
    {
        return RatepayFacadeMockBuilder::build($adapter, $this);
    }

    /**
     * @param \Orm\Zed\Ratepay\Persistence\SpyPaymentRatepayTransactionStatusLog $statusLog
     * @param \Generated\Shared\Transfer\RatepayTransactionResponseTransfer $response
     *
     * @return void
     */
    protected function matchStatusLogWithResponse(
        SpyPaymentRatepayTransactionStatusLog $statusLog,
        RatepayTransactionResponseTransfer $response
    ) {
        $this->assertEquals($response->getProcessingCode(), $statusLog->getProcessingCode());
        $this->assertEquals($response->getProcessingResult(), $statusLog->getProcessingResult());
        $this->assertEquals($response->getProcessingStatus(), $statusLog->getProcessingStatus());
        $this->assertEquals($response->getProcessingStatusCode(), $statusLog->getProcessingStatusCode());
        $this->assertEquals($response->getProcessingReason(), $statusLog->getProcessingReason());
        $this->assertEquals($response->getProcessingReasonCode(), $statusLog->getProcessingReasonCode());
        $this->assertEquals($response->getProcessingReturn(), $statusLog->getProcessingReturn());
        $this->assertEquals($response->getProcessingReturnCode(), $statusLog->getProcessingReturnCode());
        $this->assertNotNull($statusLog->getIdentificationTransactionid());
        $this->assertNotNull($statusLog->getIdentificationUniqueid());
        $this->assertNotNull($statusLog->getIdentificationShortid());
        $this->assertNotNull($statusLog->getProcessingTimestamp());
    }

}
