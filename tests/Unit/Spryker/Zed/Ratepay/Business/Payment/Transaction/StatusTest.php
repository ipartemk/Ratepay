<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Ratepay\Business\Payment\Transaction;

use Orm\Zed\Ratepay\Persistence\SpyPaymentRatepay;
use Orm\Zed\Ratepay\Persistence\SpyPaymentRatepayQuery;
use Spryker\Zed\Ratepay\Business\Api\Constants as ApiConstants;
use Spryker\Zed\Ratepay\Persistence\RatepayQueryContainerInterface;
use \Generated\Shared\Transfer\OrderTransfer;
use \Spryker\Zed\Ratepay\Business\Status\TransactionStatus;

class StatusTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return bool
     */
    public function testPaymentConfirmed()
    {
        $constantResults = [
            ApiConstants::REQUEST_MODEL_PAYMENT_INIT => false,
            ApiConstants::REQUEST_MODEL_PAYMENT_CONFIRM => true,
            ApiConstants::REQUEST_MODEL_PAYMENT_REQUEST => false,
            ApiConstants::REQUEST_MODEL_PAYMENT_CHANGE => false,
            ApiConstants::REQUEST_MODEL_DELIVER_CONFIRM => true,
        ];

        foreach ($constantResults as $constant => $expectedResult) {
            $statusTransaction = $this->createStatusTransaction(
                $this->getQueryContainerMock(ApiConstants::REQUEST_CODE_SUCCESS_MATRIX[$constant])
            );
            $this->assertEquals($expectedResult, $statusTransaction->isPaymentConfirmed($this->getOrderTransfer()));
        }

    }

    /**
     * @return bool
     */
    public function testDeliveryConfirmed()
    {
        $constantResults = [
            ApiConstants::REQUEST_MODEL_PAYMENT_INIT => false,
            ApiConstants::REQUEST_MODEL_PAYMENT_CONFIRM => false,
            ApiConstants::REQUEST_MODEL_PAYMENT_REQUEST => false,
            ApiConstants::REQUEST_MODEL_PAYMENT_CHANGE => false,
            ApiConstants::REQUEST_MODEL_DELIVER_CONFIRM => true,
        ];

        foreach ($constantResults as $constant => $expectedResult) {
            $statusTransaction = $this->createStatusTransaction(
                $this->getQueryContainerMock(ApiConstants::REQUEST_CODE_SUCCESS_MATRIX[$constant])
            );
            $this->assertEquals($expectedResult, $statusTransaction->isDeliveryConfirmed($this->getOrderTransfer()));
        }
    }

    /**
     * @return bool
     */
    public function testCancellationConfirmed()
    {
        $constantResults = [
            ApiConstants::REQUEST_MODEL_PAYMENT_INIT => false,
            ApiConstants::REQUEST_MODEL_PAYMENT_CONFIRM => false,
            ApiConstants::REQUEST_MODEL_PAYMENT_REQUEST => false,
            ApiConstants::REQUEST_MODEL_PAYMENT_CHANGE => true,
            ApiConstants::REQUEST_MODEL_DELIVER_CONFIRM => false,
        ];

        foreach ($constantResults as $constant => $expectedResult) {
            $statusTransaction = $this->createStatusTransaction(
                $this->getQueryContainerMock(ApiConstants::REQUEST_CODE_SUCCESS_MATRIX[$constant])
            );
            $this->assertEquals($expectedResult, $statusTransaction->isCancellationConfirmed($this->getOrderTransfer()));
        }
    }

    /**
     * @return bool
     */
    public function testRefundApproved()
    {
        $constantResults = [
            ApiConstants::REQUEST_MODEL_PAYMENT_INIT => false,
            ApiConstants::REQUEST_MODEL_PAYMENT_CONFIRM => false,
            ApiConstants::REQUEST_MODEL_PAYMENT_REQUEST => false,
            ApiConstants::REQUEST_MODEL_PAYMENT_CHANGE => true,
            ApiConstants::REQUEST_MODEL_DELIVER_CONFIRM => false,
        ];

        foreach ($constantResults as $constant => $expectedResult) {
            $statusTransaction = $this->createStatusTransaction(
                $this->getQueryContainerMock(ApiConstants::REQUEST_CODE_SUCCESS_MATRIX[$constant])
            );
            $this->assertEquals($expectedResult, $statusTransaction->isRefundApproved($this->getOrderTransfer()));
        }
    }

    /**
     * @param \Orm\Zed\Ratepay\Persistence\SpyPaymentRatepay $queryContainerMock
     *
     * @return \Spryker\Zed\Ratepay\Business\Status\TransactionStatus
     */
    protected function createStatusTransaction($queryContainerMock)
    {
        return new TransactionStatus(
            $queryContainerMock
        );
    }

    /**
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function getOrderTransfer()
    {
        $orderTransfer = new OrderTransfer();
        $orderTransfer
            ->setIdSalesOrder('TEST--1')
            ->setOrderReference('TEST--1');

        return $orderTransfer;
    }

    /**
     * @param int $paymentResultCode
     *
     * @return \Orm\Zed\Ratepay\Persistence\SpyPaymentRatepay
     */
    protected function getQueryContainerMock($paymentResultCode)
    {
        $queryContainer = $this->getMock(RatepayQueryContainerInterface::class);
        $queryPaymentsMock = $this->getMock(SpyPaymentRatepayQuery::class, ['findByFkSalesOrder', 'getFirst']);

        $ratepayPaymentEntity = new SpyPaymentRatepay();

        $ratepayPaymentEntity->setResultCode($paymentResultCode);

        $queryPaymentsMock->method('findByFkSalesOrder')->willReturnSelf();
        $queryPaymentsMock->method('getFirst')->willReturn($ratepayPaymentEntity);
        $queryContainer->method('queryPayments')->willReturn($queryPaymentsMock);

        return $queryContainer;
    }

}
