<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\Ratepay\Business\Order\MethodMapper;

use Orm\Zed\Ratepay\Persistence\SpyPaymentRatepay;
use Generated\Shared\Transfer\RatepayPaymentElvTransfer;
use Spryker\Shared\Ratepay\RatepayConstants;
use Generated\Shared\Transfer\QuoteTransfer;

class Elv extends AbstractMapper
{
    const METHOD = RatepayConstants::METHOD_ELV;

    public function getMethodName()
    {
        return static::METHOD;
    }

    /**
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return RatepayPaymentElvTransfer
     */
    protected function getPaymentTransfer(QuoteTransfer $quoteTransfer)
    {
        return $quoteTransfer->getPayment()->getRatepayElv();
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
            ->setBankAccountIban($paymentTransfer->requireBankAccountIban()->getBankAccountIban());
    }

}
