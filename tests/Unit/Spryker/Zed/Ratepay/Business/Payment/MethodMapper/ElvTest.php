<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Ratepay\Business\Payment\MethodMapper;

use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\RatepayPaymentElvTransfer;
use Spryker\Zed\Ratepay\Business\Payment\Method\Elv;

class ElvTest extends AbstractMethodMapperTest
{

    /**
     * @return \Spryker\Zed\Ratepay\Business\Payment\Method\MethodInterface
     */
    public function getPaymentMethod()
    {
        return new Elv(
            $this->createApiRequestFactory(),
            $this->createMapperFactory(),
            $this->getQueryContainerMock()
        );
    }

    /**
     * @return \Generated\Shared\Transfer\PaymentTransfer
     */
    protected function getPaymentTransfer()
    {
        $paymentTransfer = new RatepayPaymentElvTransfer();
        $this->setRatepayPaymentEntityData($paymentTransfer);

        $payment = new PaymentTransfer();
        $payment->setRatepayElv($paymentTransfer);

        return $payment;
    }

    /**
     *
     * @param \Orm\Zed\Ratepay\Persistence\SpyPaymentRatepay|\Generated\Shared\Transfer\RatepayPaymentElvTransfer $ratepayPaymentEntity
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
            ->setPaymentType('ELV')
            ->setTransactionId('58-201604122719694')
            ->setTransactionShortId('5QTZ.2VWD.OMWW.9D3E')
            ->setDeviceFingerprint('122356')

            ->setBankAccountBic('XXXXXXXXXXX')
            ->setBankAccountIban('XXXX XXXX XXXX XXXX XXXX XX')
            ->setBankAccountHolder('TestHolder');
    }

    /**
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Request $request
     *
     * @return void
     */
    protected function testPaymentSpecificRequestData($request)
    {
        $this->assertEquals('ELV', $request->getPayment()->getMethod());

        $this->assertNull($request->getPayment()->getInstallmentDetails());
        $this->assertNull($request->getPayment()->getDebitPayType());

        $this->assertEquals('TestHolder', $request->getCustomer()->getBankAccount()->getOwner());
        $this->assertEquals('XXXXXXXXXXX', $request->getCustomer()->getBankAccount()->getBicSwift());
        $this->assertEquals('XXXX XXXX XXXX XXXX XXXX XX', $request->getCustomer()->getBankAccount()->getIban());
    }

}
