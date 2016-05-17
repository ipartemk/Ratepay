<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Ratepay\Business\Payment;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RatepayPaymentInvoiceTransfer;
use Orm\Zed\Ratepay\Persistence\SpyPaymentRatepayQuery;
use Spryker\Shared\Ratepay\RatepayConstants;
use Spryker\Zed\Ratepay\Business\Api\Constants;
use Spryker\Zed\Ratepay\Business\Api\Converter\ConverterFactory;
use Spryker\Zed\Ratepay\Business\Api\Model\Parts\Head;
use Spryker\Zed\Ratepay\Business\Api\Model\Parts\InstallmentCalculation;
use Spryker\Zed\Ratepay\Business\Api\Model\Parts\Payment;
use Spryker\Zed\Ratepay\Business\Api\Model\Payment\Calculation;
use Spryker\Zed\Ratepay\Persistence\RatepayQueryContainerInterface;
use Unit\Spryker\Zed\Ratepay\Business\Api\Response\Response;

class BasePaymentTest extends Test
{

    /**
     * @param string $className
     * @param array $additionalMockMethods
     *
     * @return \Spryker\Zed\Ratepay\Business\Payment\Handler\Transaction\QuoteTransactionInterface|\Spryker\Zed\Ratepay\Business\Payment\Handler\Transaction\OrderTransactionInterface
     */
    protected function getTransactionHandlerObject($className, $additionalMockMethods = [])
    {

        $executionAdapter = $this->getMockBuilder('\Spryker\Zed\Ratepay\Business\Api\Adapter\Http\Guzzle')
            ->disableOriginalConstructor()
            ->setMethods(['sendRequest', 'getMethodMapper'])
            ->getMock();
        $executionAdapter->method('sendRequest')
            ->willReturn((new Response())->getTestPaymentConfirmResponseData());

        foreach ($additionalMockMethods as $method => $return) {
            $executionAdapter->method($method)
                ->willReturn($return);

        }

        $converterFactory = new ConverterFactory();

        $transactionHandler = $this->getMockBuilder($className)
            ->setConstructorArgs([
                $executionAdapter,
                $converterFactory,
                $this->mockRatepayQueryContainer()
            ])
            ->setMethods(['logInfo'])
            ->getMock();
        $transactionHandler->method('logInfo')
            ->willReturn(null);

        return $transactionHandler;
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
        $paymentTransfer = new PaymentTransfer();
        $paymentTransfer->setPaymentMethod($paymentMethod);

        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->setPayment($paymentTransfer);

        return $quoteTransfer;
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Payment\Method\Invoice
     */
    protected function mockMethodInvoice()
    {
        $paymentInit = $this->mockModelPaymentInit();

        $paymentTransfer = new RatepayPaymentInvoiceTransfer();

        $invoiceMethod = $this->getMockBuilder('\Spryker\Zed\Ratepay\Business\Payment\Method\Invoice')
            ->disableOriginalConstructor()
            ->getMock();
        $invoiceMethod->method('getMethodName')
            ->willReturn(RatepayConstants::METHOD_INVOICE);
        $invoiceMethod->method('paymentInit')
            ->willReturn($paymentInit);
        $invoiceMethod->method('paymentRequest')
            ->willReturn($paymentInit);
        $invoiceMethod->method('paymentConfirm')
            ->willReturn($paymentInit);
        $invoiceMethod->method('paymentCancel')
            ->willReturn($paymentInit);
        $invoiceMethod->method('deliveryConfirm')
            ->willReturn($paymentInit);
        $invoiceMethod->method('paymentRefund')
            ->willReturn($paymentInit);
        $invoiceMethod->method('getPaymentData')
            ->willReturn($paymentTransfer);

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
        $paymentTransfer = new RatepayPaymentInvoiceTransfer();

        $installmentMethod = $this->getMockBuilder('\Spryker\Zed\Ratepay\Business\Payment\Method\Installment')
            ->disableOriginalConstructor()
            ->getMock();
        $installmentMethod->method('getMethodName')
            ->willReturn(RatepayConstants::METHOD_INSTALLMENT);
        $installmentMethod->method('paymentInit')
            ->willReturn($payment);
        $installmentMethod->method('paymentRequest')
            ->willReturn($payment);
        $installmentMethod->method('paymentConfirm')
            ->willReturn($payment);
        $installmentMethod->method('configurationRequest')
            ->willReturn($this->mockModelPaymentConfiguration());
        $installmentMethod->method('calculationRequest')
            ->willReturn($this->mockModelPaymentCalculation());
        $installmentMethod->method('getPaymentData')
            ->willReturn($paymentTransfer);

        return $installmentMethod;
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Request
     */
    protected function mockModelPaymentRequest()
    {
        $modelPaymentRequest = $this->getMockBuilder('\Spryker\Zed\Ratepay\Business\Api\Model\Payment\Request')
            ->disableOriginalConstructor()
            ->getMock();
        $modelPaymentRequest->method('getHead')
            ->willReturn($this->mockModelPartHead());
        $modelPaymentRequest->method('getPayment')
            ->willReturn($this->mockModelPartPayment());

        return $modelPaymentRequest;
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Init
     */
    protected function mockModelPaymentInit()
    {
        $paymentInit = $this->getMockBuilder('\Spryker\Zed\Ratepay\Business\Api\Model\Payment\Init')
            ->disableOriginalConstructor()
            ->setMethods(['getHead', 'getPayment'])
            ->getMock();
        $paymentInit->method('getHead')
            ->willReturn($this->mockModelPartHead());
        $paymentInit->method('getPayment')
            ->willReturn($this->mockModelPartPayment());

        return $paymentInit;
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Configuration
     */
    protected function mockModelPaymentConfiguration()
    {
        $paymentConfiguration = $this->getMockBuilder('\Spryker\Zed\Ratepay\Business\Api\Model\Payment\Configuration')
            ->disableOriginalConstructor()
            ->setMethods(['getHead', 'getPayment'])
            ->getMock();
        $paymentConfiguration->method('getHead')
            ->willReturn($this->mockModelPartHead());
        $paymentConfiguration->method('getPayment')
            ->willReturn($this->mockModelPartPayment());

        return $paymentConfiguration;
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Calculation
     */
    protected function mockModelPaymentCalculation()
    {
        $paymentCalculation = new Calculation(
            $this->mockModelPartHead(),
            $this->mockModelPartInstallmentCalculation()
        );

        return $paymentCalculation;
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Parts\Head
     */
    protected function mockModelPartHead()
    {
        $modelPartHead = new Head('s1', 'p1', 'c1');
        $modelPartHead->setOrderId(1)
            ->setOperation(Constants::REQUEST_MODEL_PAYMENT_REQUEST)
            ->setTransactionId('tr1')
            ->setTransactionShortId('tr1_short');

        return $modelPartHead;
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Parts\Payment
     */
    protected function mockModelPartPayment()
    {
        $modelPartPayment = new Payment();
        $modelPartPayment->setMethod('');

        return $modelPartPayment;
    }

    /**
     * @param string $subType
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Parts\InstallmentCalculation
     */
    protected function mockModelPartInstallmentCalculation($subType = 'calculation_by_rate')
    {
        $modelPartInstallmentCalculation = new InstallmentCalculation();
        $modelPartInstallmentCalculation->setSubType($subType);

        return $modelPartInstallmentCalculation;
    }

    /**
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function mockOrderTransfer()
    {
        $orderTransfer = new OrderTransfer();
        $orderTransfer->setIdSalesOrder(1);

        return $orderTransfer;
    }

}
