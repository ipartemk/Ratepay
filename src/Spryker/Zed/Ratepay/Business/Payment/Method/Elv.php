<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\Ratepay\Business\Payment\Method;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RatepayPaymentElvTransfer;
use Spryker\Shared\Ratepay\RatepayConstants;

/**
 * Ratepay Elv payment method.
 */
class Elv extends AbstractMethod
{

    /**
     * @const Payment method code.
     */
    const METHOD = RatepayConstants::METHOD_ELV;

    /**
     * @return string
     */
    public function getMethodName()
    {
        return static::METHOD;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\RatepayPaymentElvTransfer
     */
    protected function getPaymentData(QuoteTransfer $quoteTransfer)
    {
        return $quoteTransfer->requirePayment()->getPayment()->requireRatepayElv()->getRatepayElv();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\RatepayPaymentElvTransfer $paymentData
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Request $request
     *
     * @return void
     */
    protected function mapPaymentData($quoteTransfer, $paymentData, $request)
    {
        parent::mapPaymentData($quoteTransfer, $paymentData, $request);
        $this->mapBankAccountData($quoteTransfer, $paymentData, $request);
    }

    /**
     *
     * @param \Orm\Zed\Ratepay\Persistence\SpyPaymentRatepay $payment
     *
     * @return \Generated\Shared\Transfer\RatepayPaymentElvTransfer
     */
    protected function getPaymentTransferObject($payment)
    {
        return new RatepayPaymentElvTransfer();
    }

}
