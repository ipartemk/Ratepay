<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Ratepay\Business\Api\Adapter\Http\Guzzle;
use Spryker\Zed\Ratepay\Business\Api\ApiFactory;
use Spryker\Zed\Ratepay\Business\Api\Converter\ConverterFactory;
use Spryker\Zed\Ratepay\Business\Api\Mapper\MapperFactory;
use Spryker\Zed\Ratepay\Business\Order\MethodMapperFactory;
use Spryker\Zed\Ratepay\Business\Order\Saver as Saver;
use Spryker\Zed\Ratepay\Business\Payment\Handler\Transaction\CancelPaymentTransaction;
use Spryker\Zed\Ratepay\Business\Payment\Handler\Transaction\CapturePaymentTransaction;
use Spryker\Zed\Ratepay\Business\Payment\Handler\Transaction\InitPaymentTransaction;
use Spryker\Zed\Ratepay\Business\Payment\Handler\Transaction\InstallmentCalculationTransaction;
use Spryker\Zed\Ratepay\Business\Payment\Handler\Transaction\InstallmentConfigurationTransaction;
use Spryker\Zed\Ratepay\Business\Payment\Handler\Transaction\PreAuthorizePaymentTransaction;
use Spryker\Zed\Ratepay\Business\Payment\Handler\Transaction\PreCheckPaymentTransaction;
use Spryker\Zed\Ratepay\Business\Payment\Handler\Transaction\RefundPaymentTransaction;
use Spryker\Zed\Ratepay\Business\Payment\Method\Elv as Elv;
use Spryker\Zed\Ratepay\Business\Payment\Method\Installment;
use Spryker\Zed\Ratepay\Business\Payment\Method\Invoice as Invoice;
use Spryker\Zed\Ratepay\Business\Payment\Method\Prepayment as Prepayment;
use Spryker\Zed\Ratepay\Business\Payment\Model\PaymentLogger;
use Spryker\Zed\Ratepay\Business\Status\TransactionStatus as TransactionStatus;

/**
 * @method \Spryker\Zed\Ratepay\Persistence\RatepayQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Ratepay\RatepayConfig getConfig()
 */
class RatepayBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @param string $gatewayUrl
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Adapter\AdapterInterface
     */
    protected function createAdapter($gatewayUrl)
    {
        return new Guzzle($gatewayUrl);
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Payment\Handler\Transaction\InitPaymentTransaction
     */
    public function createInitPaymentTransactionHandler()
    {
        $transactionHandler = new InitPaymentTransaction(
            $this->createAdapter($this->getConfig()->getTransactionGatewayUrl()),
            $this->createConverterFactory(),
            $this->createPaymentLogger(),
            $this->getQueryContainer()
        );

        $this->registerAllMethodMappers($transactionHandler);

        return $transactionHandler;
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Payment\Handler\Transaction\PreCheckPaymentTransaction
     */
    public function createPreCheckPaymentTransactionHandler()
    {
        $transactionHandler = new PreCheckPaymentTransaction(
            $this->createAdapter($this->getConfig()->getTransactionGatewayUrl()),
            $this->createConverterFactory(),
            $this->createPaymentLogger(),
            $this->getQueryContainer()
        );

        $this->registerAllMethodMappers($transactionHandler);

        return $transactionHandler;
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Payment\Handler\Transaction\CapturePaymentTransaction
     */
    public function createPreAuthorizePaymentTransactionHandler()
    {
        $transactionHandler = new PreAuthorizePaymentTransaction(
            $this->createAdapter($this->getConfig()->getTransactionGatewayUrl()),
            $this->createConverterFactory(),
            $this->createPaymentLogger(),
            $this->getQueryContainer()
        );

        $this->registerAllMethodMappers($transactionHandler);

        return $transactionHandler;
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Payment\Handler\Transaction\CapturePaymentTransaction
     */
    public function createCapturePaymentTransactionHandler()
    {
        $transactionHandler = new CapturePaymentTransaction(
            $this->createAdapter($this->getConfig()->getTransactionGatewayUrl()),
            $this->createConverterFactory(),
            $this->createPaymentLogger(),
            $this->getQueryContainer()
        );

        $this->registerAllMethodMappers($transactionHandler);

        return $transactionHandler;
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Payment\Handler\Transaction\CancelPaymentTransaction
     */
    public function createCancelPaymentTransactionHandler()
    {
        $transactionHandler = new CancelPaymentTransaction(
            $this->createAdapter($this->getConfig()->getTransactionGatewayUrl()),
            $this->createConverterFactory(),
            $this->createPaymentLogger(),
            $this->getQueryContainer()
        );

        $this->registerAllMethodMappers($transactionHandler);

        return $transactionHandler;
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Payment\Handler\Transaction\RefundPaymentTransaction
     */
    public function createRefundPaymentTransactionHandler()
    {
        $transactionHandler = new RefundPaymentTransaction(
            $this->createAdapter($this->getConfig()->getTransactionGatewayUrl()),
            $this->createConverterFactory(),
            $this->createPaymentLogger(),
            $this->getQueryContainer()
        );

        $this->registerAllMethodMappers($transactionHandler);

        return $transactionHandler;
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Payment\Handler\Transaction\InstallmentConfigurationTransaction
     */
    public function createInstallmentConfigurationTransactionHandler()
    {
        $transactionHandler = new InstallmentConfigurationTransaction(
            $this->createAdapter($this->getConfig()->getTransactionGatewayUrl()),
            $this->createConverterFactory(),
            $this->createPaymentLogger(),
            $this->getQueryContainer()
        );

        $transactionHandler->registerMethodMapper($this->createInstallment());

        return $transactionHandler;
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Payment\Handler\Transaction\InstallmentCalculationTransaction
     */
    public function createInstallmentCalculationTransactionHandler()
    {
        $transactionHandler = new InstallmentCalculationTransaction(
            $this->createAdapter($this->getConfig()->getTransactionGatewayUrl()),
            $this->createConverterFactory(),
            $this->createPaymentLogger(),
            $this->getQueryContainer()
        );

        $transactionHandler->registerMethodMapper($this->createInstallment());

        return $transactionHandler;
    }

    /**
     * @param \Spryker\Zed\Ratepay\Business\Payment\Handler\Transaction\CheckoutTransactionInterface|\Spryker\Zed\Ratepay\Business\Payment\Handler\Transaction\OrderTransactionInterface $transactionHandler
     * @return void
     */
    protected function registerAllMethodMappers($transactionHandler)
    {
        $transactionHandler->registerMethodMapper($this->createInvoice());
        $transactionHandler->registerMethodMapper($this->createElv());
        $transactionHandler->registerMethodMapper($this->createPrepayment());
        $transactionHandler->registerMethodMapper($this->createInstallment());
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Status\TransactionStatus
     */
    public function createStatusTransaction()
    {
        return new TransactionStatus(
            $this->getQueryContainer()
        );
    }

    /**
     * @return Api\Model\RequestModelFactoryInterface
     */
    public function createApiRequestFactory()
    {
        $factory = new ApiFactory();

        return $factory->createRequestModelFactory();
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Order\MethodMapperFactory
     */
    public function getMethodMapperFactory()
    {
        return new MethodMapperFactory();
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Payment\Model\PaymentLogger
     */
    protected function createPaymentLogger()
    {
        return new PaymentLogger();
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Order\SaverInterface
     */
    public function createOrderSaver()
    {
        return new Saver();
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Api\Converter\ConverterFactory
     */
    protected function createConverterFactory()
    {
        return new ConverterFactory();
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Api\Mapper\MapperFactory
     */
    protected function createMapperFactory()
    {
        return new MapperFactory();
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Payment\Method\Invoice
     */
    public function createInvoice()
    {
        return new Invoice(
            $this->createApiRequestFactory(),
            $this->createMapperFactory(),
            $this->getQueryContainer()
        );
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Payment\Method\Elv
     */
    public function createElv()
    {
        return new Elv(
            $this->createApiRequestFactory(),
            $this->createMapperFactory(),
            $this->getQueryContainer()
        );
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Payment\Method\Prepayment
     */
    public function createPrepayment()
    {
        return new Prepayment(
            $this->createApiRequestFactory(),
            $this->createMapperFactory(),
            $this->getQueryContainer()
        );
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Payment\Method\Installment
     */
    public function createInstallment()
    {
        return new Installment(
            $this->createApiRequestFactory(),
            $this->createMapperFactory(),
            $this->getQueryContainer()
        );
    }

}
