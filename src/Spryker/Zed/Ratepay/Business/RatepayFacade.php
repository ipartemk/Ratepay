<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Ratepay\Business\RatepayBusinessFactory getFactory()
 */
class RatepayFacade extends AbstractFacade implements RatepayFacadeInterface
{

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    public function saveOrderPayment(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer)
    {
        $paymentMapper = $this->getFactory()
            ->getMethodMapperFactory()
            ->createPaymentTransactionHandler()
            ->prepareMethodMapper($quoteTransfer);

        $this->getFactory()
             ->createOrderSaver($quoteTransfer, $checkoutResponseTransfer, $paymentMapper)
             ->saveOrderPayment();
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\RatepayResponseTransfer
     */
    public function initPayment(QuoteTransfer $quoteTransfer)
    {
        return $this
            ->getFactory()
            ->createInitPaymentTransactionHandler()
            ->request($quoteTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\RatepayResponseTransfer
     */
    public function preCheckPayment(QuoteTransfer $quoteTransfer)
    {
        return $this
            ->getFactory()
            ->createPreCheckPaymentTransactionHandler()
            ->request($quoteTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\RatepayResponseTransfer
     */
    public function preAuthorizePayment(OrderTransfer $orderTransfer)
    {
        return $this
            ->getFactory()
            ->createPreAuthorizePaymentTransactionHandler()
            ->request($orderTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer[] $orderItems
     *
     * @return \Generated\Shared\Transfer\RatepayResponseTransfer
     */
    public function capturePayment(OrderTransfer $orderTransfer, array $orderItems)
    {
        return $this
            ->getFactory()
            ->createCapturePaymentTransactionHandler()
            ->request($orderTransfer, $orderItems);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer[] $orderItems
     *
     * @return \Generated\Shared\Transfer\RatepayResponseTransfer
     */
    public function cancelPayment(OrderTransfer $orderTransfer, array $orderItems)
    {
        return $this
            ->getFactory()
            ->createCancelPaymentTransactionHandler()
            ->request($orderTransfer, $orderItems);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer[] $orderItems
     *
     * @return \Generated\Shared\Transfer\RatepayResponseTransfer
     */
    public function refundPayment(OrderTransfer $orderTransfer, array $orderItems)
    {
        return $this
            ->getFactory()
            ->createRefundPaymentTransactionHandler()
            ->request($orderTransfer, $orderItems);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\RatepayInstallmentConfigurationResponseTransfer
     */
    public function installmentConfiguration(QuoteTransfer $quoteTransfer)
    {
        return $this
            ->getFactory()
            ->createInstallmentConfigurationTransactionHandler()
            ->request($quoteTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\RatepayInstallmentCalculationResponseTransfer
     */
    public function installmentCalculation(QuoteTransfer $quoteTransfer)
    {
        return $this
            ->getFactory()
            ->createInstallmentCalculationTransactionHandler()
            ->request($quoteTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isPreAuthorizationApproved(OrderTransfer $orderTransfer)
    {
        return $this
            ->getFactory()
            ->createStatusTransaction()
            ->isPreAuthorizationApproved($orderTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isCaptureApproved(OrderTransfer $orderTransfer)
    {
        return $this
            ->getFactory()
            ->createStatusTransaction()
            ->isCaptureApproved($orderTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isRefundApproved(OrderTransfer $orderTransfer)
    {
        return $this
            ->getFactory()
            ->createStatusTransaction()
            ->isRefundApproved($orderTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isCancellationConfirmed(OrderTransfer $orderTransfer)
    {
        return $this
            ->getFactory()
            ->createStatusTransaction()
            ->isCancellationConfirmed($orderTransfer);
    }

}
