<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\Ratepay\Business\Order\MethodMapper;

use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Ratepay\Persistence\SpyPaymentRatepay;
use Spryker\Shared\Ratepay\RatepayConstants;

class Installment extends AbstractMapper
{

    /**
     * @const string Method name.
     */
    const METHOD = RatepayConstants::METHOD_INSTALLMENT;

    /**
     * @return string
     */
    public function getMethodName()
    {
        return static::METHOD;
    }

    /**
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\RatepayPaymentInstallmentTransfer
     */
    protected function getPaymentTransfer(QuoteTransfer $quoteTransfer)
    {
        return $quoteTransfer->getPayment()->getRatepayInstallment();
    }

    /**
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Orm\Zed\Ratepay\Persistence\SpyPaymentRatepay $payment
     *
     * @return void
     */
    public function mapMethodDataToPayment(QuoteTransfer $quoteTransfer, SpyPaymentRatepay $payment)
    {
        parent::mapMethodDataToPayment($quoteTransfer, $payment);

        $paymentTransfer = $this->getPaymentTransfer($quoteTransfer);
        $payment->setBankAccountBic($paymentTransfer->requireBankAccountBic()->getBankAccountBic())
            ->setBankAccountHolder($paymentTransfer->requireBankAccountHolder()->getBankAccountHolder())
            ->setBankAccountIban($paymentTransfer->requireBankAccountIban()->getBankAccountIban())

            ->setDebitPayType($paymentTransfer->requireDebitPayType()->getDebitPayType())

            ->setInstallmentAmount($paymentTransfer->requireInstallmentAmount()->getInstallmentAmount())
            ->setInstallmentInterestRate($paymentTransfer->requireInstallmentInterestRate()->getInstallmentInterestRate())
            ->setInstallmentLastAmount($paymentTransfer->requireInstallmentLastAmount()->getInstallmentLastAmount())
            ->setInstallmentNumber($paymentTransfer->requireInstallmentNumber()->getInstallmentNumber())
            ->setInstallmentPaymentFirstDay($paymentTransfer->requireInstallmentPaymentFirstDay()->getInstallmentPaymentFirstDay());
    }

}
