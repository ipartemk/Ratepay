<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;
use Spryker\Zed\Messenger\Business\Model\MessengerInterface;

/**
 * @method \Spryker\Zed\Ratepay\Business\RatepayBusinessFactory getFactory()
 */
class RatepayFacade extends AbstractFacade implements RatepayFacadeInterface
{

    /**
     * Specification:
     * - Saves order payment method data according to quote and checkout response transfer data.
     *
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
     * Specification:
     * - Process init payment request to Ratepay gateway to retrieve transaction data.
     *
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
     * Specification:
     * - Process pre-check payment request to Ratepay gateway.
     *
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
     * Specification:
     * - Process payment confirmation request to Ratepay gateway.
     *
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
     * Specification:
     * - Process capture payment request to Ratepay gateway.
     *
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
     * Specification:
     * - Process cancel payment request to Ratepay gateway.
     *
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
     * Specification:
     * - Process refund payment request to Ratepay gateway.
     *
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
     * Specification:
     * - Process installment payment method calculator configuration request to Ratepay gateway.
     *
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
     * Specification:
     * - Process installment payment method calculator calculation request to Ratepay gateway.
     *
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
     * Specification:
     * - Checks if pre-authorization API request got success response from Ratepay gateway.
     *
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
     * Specification:
     * - Checks if capture API request got success response from Ratepay gateway.
     *
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
     * Specification:
     * - Checks if payment refund API request got success response from Ratepay gateway.
     *
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
     * Specification:
     * - Checks if payment cancellation API request got success response from Ratepay gateway.
     *
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

    /**
     * Specification:
     * - Expands cart items with necessary for Ratepay information (short_description, long_description, etc).
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $change
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandItems(CartChangeTransfer $change)
    {
        return $this->getFactory()->createProductExpander()->expandItems($change);
    }

    /**
     * Specification:
     * - Install bundle translations to project glossary.
     *
     * @api
     *
     * @param \Spryker\Zed\Messenger\Business\Model\MessengerInterface|null $messenger
     *
     * @return void
     */
    public function install(MessengerInterface $messenger = null)
    {
        $this->getFactory()->createInstaller($messenger)->install();
    }

}
