<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\Ratepay\Business\Request\Payment;

use Functional\Spryker\Zed\Ratepay\Business\Request\AbstractFacadeTest;
use Generated\Shared\Transfer\RatepayPaymentInvoiceTransfer;
use Spryker\Shared\Ratepay\RatepayConstants;

abstract class InvoiceAbstractTest extends AbstractFacadeTest
{

    /**
     * @const Payment method code.
     */
    const PAYMENT_METHOD = RatepayConstants::METHOD_INVOICE;

    /**
     * @return \Generated\Shared\Transfer\RatepayPaymentInvoiceTransfer
     */
    protected function getRatepayPaymentMethodTransfer()
    {
        return new RatepayPaymentInvoiceTransfer();
    }

    /**
     * @return mixed
     */
    protected function getPaymentTransferFromQuote()
    {
        return $this->quoteTransfer->getPayment()->getRatepayInvoice();
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentTransfer $payment
     * @param \Spryker\Shared\Transfer\TransferInterface $paymentTransfer
     *
     * @return void
     */
    protected function setRatepayPaymentDataToPaymentTransfer($payment, $paymentTransfer)
    {
        $payment->setRatepayInvoice($paymentTransfer);
    }

    /**
     *
     * @param \Orm\Zed\Ratepay\Persistence\SpyPaymentRatepay|\Generated\Shared\Transfer\RatepayPaymentInvoiceTransfer $ratepayPaymentEntity
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
            ->setPhone('123456789')
            ->setIpAddress('127.0.0.1')
            ->setPaymentType('INVOICE')
            ->setTransactionId('58-201604122719694')
            ->setTransactionShortId('5QTZ.2VWD.OMWW.9D3E')
            ->setDeviceFingerprint('122356');
    }

}
