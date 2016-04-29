<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Ratepay\Business\Payment;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\RatepayPaymentInvoiceTransfer;
use Orm\Zed\Ratepay\Persistence\SpyPaymentRatepayQuery;
use Spryker\Shared\Ratepay\RatepayConstants;
use Spryker\Zed\Ratepay\Business\Api\Constants;
use Spryker\Zed\Ratepay\Business\Api\Converter\ConverterFactory;
use Spryker\Zed\Ratepay\Persistence\RatepayQueryContainerInterface;
use Unit\Spryker\Zed\Ratepay\Business\Api\Response\Response;

class BasePaymentTest extends Test
{

    /**
     * @var \Mockery;
     */
    protected $mockery;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->mockery = new \Mockery();
    }

    /**
     * @param string $className
     * @param array $additionalMockMethods
     *
     * @return \Spryker\Zed\Ratepay\Business\Payment\Handler\Transaction\QuoteTransactionInterface|\Spryker\Zed\Ratepay\Business\Payment\Handler\Transaction\OrderTransactionInterface
     */
    protected function getTransactionHandlerObject($className, $additionalMockMethods = [])
    {
        $executionAdapter = $this->mockery->mock('\Spryker\Zed\Ratepay\Business\Api\Adapter\Http\Guzzle');
        $executionAdapter->shouldReceive('sendRequest')
            ->andReturn((new Response())->getTestPaymentConfirmResponseData());

        foreach ($additionalMockMethods as $method => $return) {
            $executionAdapter->shouldReceive($method)
                ->andReturn($return);
        }

        $converterFactory = new ConverterFactory();
        $paymentLogger = $this->mockery->mock('\Spryker\Zed\Ratepay\Business\Payment\Model\PaymentLogger');
        $paymentLogger->shouldReceive('info')
            ->andReturn(null);

        return new $className(
            $executionAdapter,
            $converterFactory,
            $paymentLogger,
            $this->mockRatepayQueryContainer()
        );
    }

    /**
     * @return \Orm\Zed\Ratepay\Persistence\SpyPaymentRatepay
     */
    protected function mockPaymentRatepay()
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
     * @return \Spryker\Zed\Ratepay\Persistence\RatepayQueryContainerInterface
     */
    protected function mockRatepayQueryContainer()
    {
        $queryContainer = $this->getMock(RatepayQueryContainerInterface::class);
        $queryPaymentsMock = $this->getMock(SpyPaymentRatepayQuery::class, ['findByFkSalesOrder', 'getFirst']);

        $queryPaymentsMock->method('findByFkSalesOrder')->willReturnSelf();
        $queryPaymentsMock->method('getFirst')->willReturn($this->mockPaymentRatepay());
        $queryContainer->method('queryPayments')->willReturn($queryPaymentsMock);

        return $queryContainer;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function mockQuoteTransfer($paymentMethod = 'INVOICE')
    {
        $quoteTransfer = $this->mockery->mock('\Generated\Shared\Transfer\QuoteTransfer');
        $quoteTransfer->shouldReceive('requirePayment')
            ->andReturn($this->mockery->self());
        $quoteTransfer->shouldReceive('getPayment')
            ->andReturn($this->mockery->self());
        $quoteTransfer->shouldReceive('requirePaymentMethod')
            ->andReturn($this->mockery->self());
        $quoteTransfer->shouldReceive('getPaymentMethod')
            ->andReturn($paymentMethod);

        return $quoteTransfer;
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Payment\Method\Invoice
     */
    protected function mockMethodInvoice()
    {
        $paymentInit = $this->mockModelPaymentInit();

        $invoiceMethod = $this->mockery->mock('\Spryker\Zed\Ratepay\Business\Payment\Method\Invoice');
        $invoiceMethod->shouldReceive('getMethodName')
            ->andReturn(RatepayConstants::METHOD_INVOICE);
        $invoiceMethod->shouldReceive('paymentInit')
            ->andReturn($paymentInit);
        $invoiceMethod->shouldReceive('paymentRequest')
            ->andReturn($paymentInit);
        $invoiceMethod->shouldReceive('paymentConfirm')
            ->andReturn($paymentInit);
        $invoiceMethod->shouldReceive('paymentCancel')
            ->andReturn($paymentInit);
        $invoiceMethod->shouldReceive('deliveryConfirm')
            ->andReturn($paymentInit);
        $invoiceMethod->shouldReceive('paymentRefund')
            ->andReturn($paymentInit);
        

        $paymentTransfer = new RatepayPaymentInvoiceTransfer();
        $invoiceMethod->shouldReceive('getPaymentData')
            ->andReturn($paymentTransfer);

        return $invoiceMethod;
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Payment\Method\Installment
     */
    protected function mockMethodInstallmentConfiguration()
    {
        $paymentConfiguration = $this->mockModelPaymentConfiguration();

        return $this->mockMethodInstallment($paymentConfiguration);
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Payment\Method\Installment
     */
    protected function mockMethodInstallmentCalculation()
    {
        $paymentCalculation = $this->mockModelPaymentCalculation();

        return $this->mockMethodInstallment($paymentCalculation);
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Payment\Method\Installment
     */
    protected function mockMethodInstallment($payment)
    {
        $installmentMethod = $this->mockery->mock('\Spryker\Zed\Ratepay\Business\Payment\Method\Installment');
        $installmentMethod->shouldReceive('getMethodName')
            ->andReturn(RatepayConstants::METHOD_INSTALLMENT);
        $installmentMethod->shouldReceive('paymentInit')
            ->andReturn($payment);
        $installmentMethod->shouldReceive('paymentRequest')
            ->andReturn($payment);
        $installmentMethod->shouldReceive('paymentConfirm')
            ->andReturn($payment);
        $installmentMethod->shouldReceive('configurationRequest')
            ->andReturn($this->mockModelPaymentConfiguration());
        $installmentMethod->shouldReceive('calculationRequest')
            ->andReturn($this->mockModelPaymentCalculation());

        $paymentTransfer = new RatepayPaymentInvoiceTransfer();
        $installmentMethod->shouldReceive('getPaymentData')
            ->andReturn($paymentTransfer);

        return $installmentMethod;
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Request
     */
    protected function mockModelPaymentRequest()
    {
        $modelPaymentRequest = $this->mockery->mock('\Spryker\Zed\Ratepay\Business\Api\Model\Payment\Request');
        $modelPaymentRequest->shouldReceive('getHead')
            ->andReturn($this->mockModelPartHead());
        $modelPaymentRequest->shouldReceive('getPayment')
            ->andReturn($this->mockModelPartPayment());

        return $modelPaymentRequest;
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Init
     */
    protected function mockModelPaymentInit()
    {
        $paymentInit = $this->mockery->mock('\Spryker\Zed\Ratepay\Business\Api\Model\Payment\Init');
        $paymentInit->shouldReceive('getHead')
            ->andReturn($this->mockModelPartHead());
        $paymentInit->shouldReceive('getPayment')
            ->andReturn($this->mockModelPartPayment());

        return $paymentInit;
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Configuration
     */
    protected function mockModelPaymentConfiguration()
    {
        $paymentConfiguration = $this->mockery->mock('\Spryker\Zed\Ratepay\Business\Api\Model\Payment\Configuration');
        $paymentConfiguration->shouldReceive('getHead')
            ->andReturn($this->mockModelPartHead());
        $paymentConfiguration->shouldReceive('getPayment')
            ->andReturn($this->mockModelPartPayment());

        return $paymentConfiguration;
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Calculation
     */
    protected function mockModelPaymentCalculation()
    {
        $paymentConfiguration = $this->mockery->mock('\Spryker\Zed\Ratepay\Business\Api\Model\Payment\Calculation');
        $paymentConfiguration->shouldReceive('getHead')
            ->andReturn($this->mockModelPartHead());
        $paymentConfiguration->shouldReceive('getPayment')
            ->andReturn($this->mockModelPartPayment());
        $paymentConfiguration->shouldReceive('getInstallmentCalculation')
            ->andReturn($this->mockModelPartInstallmentCalculation());

        return $paymentConfiguration;
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Parts\Head
     */
    protected function mockModelPartHead()
    {
        $modelPartHead = $this->mockery->mock('\Spryker\Zed\Ratepay\Business\Api\Model\Parts\Head');
        $modelPartHead->shouldReceive('getOrderId')
            ->andReturn(1);
        $modelPartHead->shouldReceive('getOperation')
            ->andReturn(Constants::REQUEST_MODEL_PAYMENT_REQUEST);
        $modelPartHead->shouldReceive('getTransactionId')
            ->andReturn('tr1');
        $modelPartHead->shouldReceive('getTransactionShortId')
            ->andReturn('tr1_short');

        return $modelPartHead;
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Parts\Payment
     */
    protected function mockModelPartPayment()
    {
        $modelPartPayment = $this->mockery->mock('\Spryker\Zed\Ratepay\Business\Api\Model\Parts\Payment');
        $modelPartPayment->shouldReceive('getMethod')
            ->andReturn('');

        return $modelPartPayment;
    }

    /**
     * @param string $subType
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Parts\InstallmentCalculation
     */
    protected function mockModelPartInstallmentCalculation($subType = 'calculation_by_rate')
    {
        $modelPartInstallmentCalculation = $this->mockery->mock('\Spryker\Zed\Ratepay\Business\Api\Model\Parts\InstallmentCalculation');
        $modelPartInstallmentCalculation->shouldReceive('getSubType')
            ->andReturn($subType);

        return $modelPartInstallmentCalculation;
    }

    /**
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function mockOrderTransfer()
    {
        $orderTransfer = $this->mockery->mock('\Generated\Shared\Transfer\OrderTransfer');
        $orderTransfer->shouldReceive('requireIdSalesOrder')
            ->andReturn($this->mockery->self());
        $orderTransfer->shouldReceive('getIdSalesOrder')
            ->andReturn(1);

        return $orderTransfer;
    }

    protected function tearDown()
    {
        parent::tearDown();

        $this->mockery->close();
    }

}
