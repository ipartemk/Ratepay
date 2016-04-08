<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Order;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Ratepay\Persistence\SpyPaymentRatepay;

class Saver implements SaverInterface
{

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    public function saveOrderPayment(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer)
    {
        $paymentEntity = new SpyPaymentRatepay();
        $idSalesOrder = $checkoutResponseTransfer->getSaveOrder()->getIdSalesOrder();
        $quotePayment = $quoteTransfer->requirePayment()->getPayment();

        $paymentTransfer = $this->getPaymentTransfer($quoteTransfer, $quotePayment->requirePaymentSelection()->getPaymentSelection());

        $paymentEntity
            ->setFkSalesOrder($idSalesOrder)
            ->setPaymentType($quotePayment->requirePaymentMethod()->getPaymentMethod())
            ->setTransactionId($paymentTransfer->requireTransactionId()->getTransactionId())
            ->setTransactionShortId($paymentTransfer->requireTransactionShortId()->getTransactionShortId())
            ->setResultCode($paymentTransfer->requireResultCode()->getResultCode())

            ->setGender($paymentTransfer->requireGender()->getGender())
            ->setDateOfBirth($paymentTransfer->requireDateOfBirth()->getDateOfBirth())
            ->setCustomerAllowCreditInquiry($paymentTransfer->requireCustomerAllowCreditInquiry()->getCustomerAllowCreditInquiry())
//            ->setDebitPayType($paymentTransfer->getPaymentType())

            ->setIpAddress($paymentTransfer->requireIpAddress()->getIpAddress())
            ->setCurrencyIso3($paymentTransfer->requireCurrencyIso3()->getCurrencyIso3());

//            ->setInstallmentAmount($paymentTransfer->getInstallmentAmount())
//            ->setInstallmentInterestRate($paymentTransfer->getInstallmentInterestRate())
//            ->setInstallmentLastAmount($paymentTransfer->getInstallmentLastAmount())
//            ->setInstallmentNumber($paymentTransfer->getInstallmentNumber())
//            ->setInstallmentPaymentFirstDay($paymentTransfer->getInstallmentPaymentFirstDay())

//            ->setBankAccountBic($paymentTransfer->getBankAccountBic())
//            ->setBankAccountHolder($paymentTransfer->getBankAccountHolder())
//            ->setBankAccountIban($paymentTransfer->getBankAccountIban());

            $paymentEntity->save();
    }


    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $paymentSelection
     *
     * @return \Generated\Shared\Transfer\RatepayPaymentInvoiceTransfer|\Generated\Shared\Transfer\RatepayPaymentElvTransfer|\Generated\Shared\Transfer\RatepayPaymentPrepaymentTransfer|\Generated\Shared\Transfer\RatepayPaymentInstallmentTransfer
     */
    protected function getPaymentTransfer(QuoteTransfer $quoteTransfer, $paymentSelection)
    {
        $method = 'get' . ucfirst($paymentSelection);
        $paymentTransfer = $quoteTransfer->getPayment()->$method();

        return $paymentTransfer;
    }
}