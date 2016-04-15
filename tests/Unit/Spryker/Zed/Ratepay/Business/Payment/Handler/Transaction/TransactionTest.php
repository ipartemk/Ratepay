<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Ratepay\Business\Payment\Handler\Transaction;

use Spryker\Zed\Ratepay\Business\Api\Converter\Converter;
use Spryker\Zed\Ratepay\Business\Payment\Handler\Transaction\Transaction;
use Unit\Spryker\Zed\Ratepay\Business\Api\Response\Response;

class TransactionTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return bool
     */
    public function testPreAuthorizationApproved()
    {
        $transaction = $this->getTransactionObject();

        //test instance.
        $this->assertInstanceOf('\Spryker\Zed\Ratepay\Business\Payment\Handler\Transaction\Transaction', $transaction);

    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Payment\Handler\Transaction\Transaction
     */
    private function getTransactionObject()
    {
        $executionAdapter = $this->getMockBuilder('\Spryker\Zed\Ratepay\Business\Api\Adapter\Http\Guzzle')
            ->disableOriginalConstructor()
            ->getMock();
        $executionAdapter->method('sendRequest')
            ->willReturn(Response::getTestPaymentConfirmResponseData());

        $converter = new Converter();

        $paymentLogger = $this->getMockBuilder('\Spryker\Zed\Ratepay\Business\Payment\Model\PaymentLogger')
            ->disableOriginalConstructor()
            ->getMock();

        $spyPaymentRatepay = $this->getMockBuilder('\Orm\Zed\Ratepay\Persistence\SpyPaymentRatepay')
            ->disableOriginalConstructor()
            ->getMock();

        $queryContainer = $this->getMockBuilder('\Spryker\Zed\Ratepay\Persistence\RatepayQueryContainer')
            ->disableOriginalConstructor()
            ->getMock();
        $queryContainer->method('queryPayments')
            ->willReturn($this->returnSelf());
        $queryContainer->method('findByFkSalesOrder')
            ->willReturn($this->returnSelf());
        $queryContainer->method('getFirst')
            ->willReturn($spyPaymentRatepay);


        return new Transaction(
            $executionAdapter,
            $converter,
            $paymentLogger,
            $queryContainer
        );
    }
    
    
    
}
