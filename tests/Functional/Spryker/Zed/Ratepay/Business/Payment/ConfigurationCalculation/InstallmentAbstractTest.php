<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\Ratepay\Business\Payment\ConfigurationCalculation;

use Functional\Spryker\Zed\Ratepay\Business\AbstractBusinessTest;
use Functional\Spryker\Zed\Ratepay\Business\Payment\RatepayFacadeMockBuilder;
use Generated\Shared\Transfer\RatepayPaymentInstallmentTransfer;
use Orm\Zed\Ratepay\Persistence\SpyPaymentRatepay;
use Spryker\Shared\Ratepay\RatepayConstants;
use Spryker\Zed\Ratepay\Business\Api\Adapter\AdapterInterface;
use Spryker\Zed\Ratepay\Business\Api\Converter\ConverterFactory;

abstract class InstallmentAbstractTest extends AbstractBusinessTest
{

    /**
     * @const Payment method code.
     */
    const PAYMENT_METHOD = RatepayConstants::METHOD_INSTALLMENT;

    /**
     * @var \Generated\Shared\Transfer\RatepayResponseTransfer
     */
    protected $responseTransfer;

    /**
     * @var \Spryker\Zed\Ratepay\Business\Api\Converter\ConverterFactory;
     */
    protected $converterFactory;

    /**
     * @var \Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    protected $orderEntity;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->quoteTransfer = $this->getQuoteTransfer();
        $this->converterFactory = new ConverterFactory();
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentTransfer $payment
     * @param \Spryker\Shared\Transfer\TransferInterface $paymentTransfer
     *
     * @return void
     */
    protected function setRatepayPaymentDataToPaymentTransfer($payment, $paymentTransfer)
    {
        $payment->setRatepayInstallment($paymentTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\RatepayPaymentInstallmentTransfer
     */
    protected function getRatepayPaymentMethodTransfer()
    {
        return (new RatepayPaymentInstallmentTransfer())
            ->setBankAccountBic('XXXXXXXXXXX')
            ->setBankAccountIban('XXXX XXXX XXXX XXXX XXXX XX')
            ->setBankAccountHolder('TestHolder');
    }

    /**
     * @return mixed
     */
    protected function getPaymentTransferFromQuote()
    {
        return $this->quoteTransfer->getPayment()->getRatepayInstallment();
    }

    /**
     * @param \Spryker\Zed\Ratepay\Business\Api\Adapter\AdapterInterface $adapter
     *
     * @return \Spryker\Zed\Ratepay\Business\RatepayFacade
     */
    protected function getFacadeMock(AdapterInterface $adapter)
    {
        return (new RatepayFacadeMockBuilder())->build($adapter, $this);
    }

    /**
     *
     * @param \Orm\Zed\Ratepay\Persistence\SpyPaymentRatepay|\Generated\Shared\Transfer\RatepayPaymentInstallmentTransfer$ratepayPaymentEntity
     *
     * @return void
     */
    protected function setRatepayPaymentEntityData($ratepayPaymentEntity)
    {
        $ratepayPaymentEntity
            ->setResultCode(503)
            ->setDateOfBirth('11.11.1991')
            ->setCurrencyIso3('EUR')
            ->setCustomerAllowCreditInquiry(true)
            ->setGender('M')
            ->setIpAddress('127.0.0.1')
            ->setPaymentType('INSTALLMENT')
            ->setTransactionId('58-201604122719694')
            ->setTransactionShortId('5QTZ.2VWD.OMWW.9D3E')
            ->setDeviceFingerprint('122356');
    }

    protected function setUpPaymentTestData()
    {
        $this->paymentEntity = (new SpyPaymentRatepay())
            ->setFkSalesOrder($this->orderEntity->getIdSalesOrder());
        $this->setRatepayPaymentEntityData($this->paymentEntity);
        $this->paymentEntity->save();
    }

}
