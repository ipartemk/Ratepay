<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Ratepay\Business\Payment\MethodMapper;

use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\RatepayPaymentInvoiceTransfer;
use Spryker\Zed\Ratepay\Business\Payment\Method\Invoice;

class InvoiceTest extends AbstractMethodMapperTest
{

    /**
     * @return \Spryker\Zed\Ratepay\Business\Payment\Method\MethodInterface
     */
    public function getPaymentMethod()
    {
        return new Invoice(
            $this->createApiRequestFactory(),
            $this->createConverter()
        );
    }

    /**
     * @return \Generated\Shared\Transfer\PaymentTransfer
     */
    protected function getPaymentTransfer()
    {
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

        return $payment;
    }

}
