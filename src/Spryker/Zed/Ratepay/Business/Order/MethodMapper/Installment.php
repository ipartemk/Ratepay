<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\Ratepay\Business\Order\MethodMapper;

use Orm\Zed\Ratepay\Persistence\SpyPaymentRatepay;
use Generated\Shared\Transfer\RatepayPaymentInstallmentTransfer;
use Spryker\Shared\Ratepay\RatepayConstants;
use Generated\Shared\Transfer\QuoteTransfer;

class Installment extends AbstractMapper
{

    /** TODO: Fix after Installment will be added. */
    const METHOD = 'RatepayConstants::METHOD_ELV';

    public function getMethodName()
    {
        return static::METHOD;
    }


    /**
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return RatepayPaymentInstallmentTransfer
     */
    protected function getPaymentTransfer(QuoteTransfer $quoteTransfer)
    {
        return $quoteTransfer->getPayment()->getRatepayInstallment();
    }

    /**
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param SpyPaymentRatepay $payment
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
