<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Ratepay\Business\Payment\Handler\Transaction;

use Spryker\Shared\Ratepay\RatepayConstants;
use Spryker\Zed\Ratepay\Business\Api\Constants;
use Spryker\Zed\Ratepay\Business\Api\Converter\Converter;
use Spryker\Zed\Ratepay\Business\Payment\Handler\Transaction\Transaction;
use Unit\Spryker\Zed\Ratepay\Business\Api\Response\Response;

class TransactionTest extends \PHPUnit_Framework_TestCase
{

    public function testPreAuthorizationApproved()
    {
        $transactionHandler = $this->getTransactionHandlerObject();

        $this->assertInstanceOf('\Spryker\Zed\Ratepay\Business\Payment\Handler\Transaction\Transaction', $transactionHandler);
    }

    public function testPreCheckPayment()
    {
        $transactionHandler = $this->getTransactionHandlerObject();
        $transactionHandler->registerMethodMapper($this->mockMethodInvoice());

        $ratepayResponseTransfer = $transactionHandler->preCheckPayment($this->mockQuoteTransfer());

        $this->assertInstanceOf('\Generated\Shared\Transfer\RatepayResponseTransfer', $ratepayResponseTransfer);

        $this->assertEquals(
            'Die Prüfung war erfolgreich. Vielen Dank, dass Sie die Zahlart Rechnung gewählt haben.',
            $ratepayResponseTransfer->getCustomerMessage()
        );
    }

    public function testPreAuthorizePayment()
    {
        $transactionHandler = $this->getTransactionHandlerObject();
        $transactionHandler->registerMethodMapper($this->mockMethodInvoice());

        $responseTransfer = $transactionHandler->preAuthorizePayment($this->mockOrderTransfer());

        $this->assertInstanceOf('\Generated\Shared\Transfer\RatepayResponseTransfer', $responseTransfer);

        $this->assertEquals(
            'Die Prüfung war erfolgreich. Vielen Dank, dass Sie die Zahlart Rechnung gewählt haben.',
            $responseTransfer->getCustomerMessage()
        );
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Payment\Handler\Transaction\Transaction
     */
    private function getTransactionHandlerObject()
    {
        $executionAdapter = $this->getMockBuilder('\Spryker\Zed\Ratepay\Business\Api\Adapter\Http\Guzzle')
            ->disableOriginalConstructor()
            ->getMock();
        $executionAdapter->method('sendRequest')
            ->willReturn(Response::getTestPaymentConfirmResponseData());

        $converter = new Converter();

        $paymentLogger = \Mockery::mock('\Spryker\Zed\Ratepay\Business\Payment\Model\PaymentLogger');
        $paymentLogger->shouldReceive('info')
            ->andReturn(null);

        $paymentRatepayQuery = $this->mockPaymentRatepayQuery();

        $queryContainer = $this->getMockBuilder('\Spryker\Zed\Ratepay\Persistence\RatepayQueryContainer')
            ->disableOriginalConstructor()
            ->getMock();
        $queryContainer->method('queryPayments')
            ->willReturn($paymentRatepayQuery);

        return new Transaction(
            $executionAdapter,
            $converter,
            $paymentLogger,
            $queryContainer
        );
    }

    /**
     * @return \Orm\Zed\Ratepay\Persistence\SpyPaymentRatepay
     */
    private function mockPaymentRatepay()
    {
        $spyPaymentRatepay = $this->getMockBuilder('\Orm\Zed\Ratepay\Persistence\SpyPaymentRatepay')
            ->disableOriginalConstructor()
            ->getMock();
        $spyPaymentRatepay->method('getPaymentType')
            ->willReturn(RatepayConstants::METHOD_INVOICE);
        $spyPaymentRatepay->method('setResultCode')
            ->willReturn($spyPaymentRatepay);
        $spyPaymentRatepay->method('save')
            ->willReturn(true);

        return $spyPaymentRatepay;
    }

    /**
     * @return \Orm\Zed\Ratepay\Persistence\SpyPaymentRatepayQuery
     */
    private function mockPaymentRatepayQuery()
    {
        $paymentRatepayQuery = \Mockery::mock('\Orm\Zed\Ratepay\Persistence\SpyPaymentRatepayQuery');
        $paymentRatepayQuery->shouldReceive('findByFkSalesOrder')
            ->andReturn(\Mockery::self());
        $paymentRatepayQuery->shouldReceive('getFirst')
            ->andReturn($this->mockPaymentRatepay());

        return $paymentRatepayQuery;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    private function mockQuoteTransfer()
    {
        $quoteTransfer = \Mockery::mock('\Generated\Shared\Transfer\QuoteTransfer');
        $quoteTransfer->shouldReceive('requirePayment')
            ->andReturn(\Mockery::self());
        $quoteTransfer->shouldReceive('getPayment')
            ->andReturn(\Mockery::self());
        $quoteTransfer->shouldReceive('requirePaymentMethod')
            ->andReturn(\Mockery::self());
        $quoteTransfer->shouldReceive('getPaymentMethod')
            ->andReturn(RatepayConstants::METHOD_INVOICE);

        return $quoteTransfer;
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Payment\Method\Invoice
     */
    private function mockMethodInvoice()
    {
        $modelPartHead = \Mockery::mock('\Spryker\Zed\Ratepay\Business\Api\Model\Parts\Head');
        $modelPartHead->shouldReceive('getOrderId')
            ->andReturn(1);
        $modelPartHead->shouldReceive('getOperation')
            ->andReturn(Constants::REQUEST_MODEL_PAYMENT_REQUEST);
        $modelPartHead->shouldReceive('getTransactionId')
            ->andReturn('tr1');
        $modelPartHead->shouldReceive('getTransactionShortId')
            ->andReturn('tr1_short');

        $paymentInit = \Mockery::mock('\Spryker\Zed\Ratepay\Business\Api\Model\Payment\Init');
        $paymentInit->shouldReceive('getHead')
            ->andReturn($modelPartHead);

        $invoiceMethod = \Mockery::mock('\Spryker\Zed\Ratepay\Business\Payment\Method\Invoice');
        $invoiceMethod->shouldReceive('getMethodName')
            ->andReturn(RatepayConstants::METHOD_INVOICE);
        $invoiceMethod->shouldReceive('paymentInit')
            ->andReturn($paymentInit);
        $invoiceMethod->shouldReceive('paymentRequest')
            ->andReturn($paymentInit);
        $invoiceMethod->shouldReceive('paymentConfirm')
            ->andReturn($paymentInit);

        return $invoiceMethod;
    }

    /**
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    private function mockOrderTransfer()
    {
        $orderTransfer = \Mockery::mock('\Generated\Shared\Transfer\OrderTransfer');
        $orderTransfer->shouldReceive('requireIdSalesOrder')
            ->andReturn(\Mockery::self());
        $orderTransfer->shouldReceive('getIdSalesOrder')
            ->andReturn(1);

        return $orderTransfer;
    }

    protected function tearDown()
    {
        \Mockery::close();
    }

}
