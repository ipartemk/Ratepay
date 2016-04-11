<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Order\MethodMapper;

use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Ratepay\Persistence\SpyPaymentRatepay;

abstract class AbstractMapper implements PaymentMethodMapperInterface
{

    /**
     * 
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\RatepayPaymentInvoiceTransfer|\Generated\Shared\Transfer\RatepayPaymentElvTransfer|\Generated\Shared\Transfer\RatepayPaymentPrepaymentTransfer|\Generated\Shared\Transfer\RatepayPaymentInstallmentTransfer
     */
    abstract protected function getPaymentTransfer(QuoteTransfer $quoteTransfer);

    /**
     * 
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param SpyPaymentRatepay $payment
     */
    public function mapMethodDataToPayment(QuoteTransfer $quoteTransfer, SpyPaymentRatepay $payment)
    {
        $paymentTransfer = $this->getPaymentTransfer($quoteTransfer);

        $payment
            ->setPaymentType($quoteTransfer->requirePayment()->getPayment()->requirePaymentMethod()->getPaymentMethod())
            ->setTransactionId($paymentTransfer->requireTransactionId()->getTransactionId())
            ->setTransactionShortId($paymentTransfer->requireTransactionShortId()->getTransactionShortId())
            ->setResultCode($paymentTransfer->requireResultCode()->getResultCode())

            ->setGender($paymentTransfer->requireGender()->getGender())
            ->setDateOfBirth($paymentTransfer->requireDateOfBirth()->getDateOfBirth())
            ->setCustomerAllowCreditInquiry($paymentTransfer->requireCustomerAllowCreditInquiry()->getCustomerAllowCreditInquiry())

            ->setIpAddress($paymentTransfer->requireIpAddress()->getIpAddress())
            ->setCurrencyIso3($paymentTransfer->requireCurrencyIso3()->getCurrencyIso3());
    }
}
