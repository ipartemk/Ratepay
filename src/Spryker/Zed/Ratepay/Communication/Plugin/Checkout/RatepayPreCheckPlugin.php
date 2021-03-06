<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Communication\Plugin\Checkout;

use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RatepayResponseTransfer;
use Spryker\Zed\Checkout\Dependency\Plugin\CheckoutPreConditionInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Ratepay\Business\RatepayFacade getFacade()
 * @method \Spryker\Zed\Ratepay\Communication\RatepayCommunicationFactory getFactory()
 */
class RatepayPreCheckPlugin extends AbstractPlugin implements CheckoutPreConditionInterface
{

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function checkCondition(
        QuoteTransfer $quoteTransfer,
        CheckoutResponseTransfer $checkoutResponseTransfer
    ) {
        $this->getFacade()->initPayment($quoteTransfer);
        $ratepayResponseTransfer = $this->getFacade()->requestPayment($quoteTransfer);
        $this->checkForErrors($ratepayResponseTransfer, $checkoutResponseTransfer);

        return $checkoutResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\RatepayResponseTransfer $ratepayResponseTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    protected function checkForErrors(RatepayResponseTransfer $ratepayResponseTransfer, CheckoutResponseTransfer $checkoutResponseTransfer)
    {
        if (!$ratepayResponseTransfer->getSuccessful()) {

            $errorMessage = $ratepayResponseTransfer->getCustomerMessage() != '' ? $ratepayResponseTransfer->getCustomerMessage() :
                $ratepayResponseTransfer->getResultText();

            $error = new CheckoutErrorTransfer();
            $error
                ->setErrorCode($ratepayResponseTransfer->getResultCode())
                ->setMessage($errorMessage);
            $checkoutResponseTransfer->addError($error);
        }
    }

}
