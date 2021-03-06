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
        $this->getFactory()
             ->createOrderSaver($quoteTransfer, $checkoutResponseTransfer)
             ->saveOrderPayment();
    }

    /**
     * Specification:
     * - Performs the init payment request to RatePAY Gateway to retrieve transaction data.
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
     * - Performs check the customer and order details payment request to RatePAY Gateway.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\RatepayResponseTransfer
     */
    public function requestPayment(QuoteTransfer $quoteTransfer)
    {
        return $this
            ->getFactory()
            ->createRequestPaymentTransactionHandler()
            ->request($quoteTransfer);
    }

    /**
     * Specification:
     * - Performs the payment confirmation request to RatePAY Gateway.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\RatepayResponseTransfer
     */
    public function confirmPayment(OrderTransfer $orderTransfer)
    {
        return $this
            ->getFactory()
            ->createConfirmPaymentTransactionHandler()
            ->request($orderTransfer);
    }

    /**
     * Specification:
     * - Performs the delivery confirmation request to RatePAY Gateway.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer[] $orderItems
     *
     * @return \Generated\Shared\Transfer\RatepayResponseTransfer
     */
    public function confirmDelivery(OrderTransfer $orderTransfer, array $orderItems)
    {
        return $this
            ->getFactory()
            ->createConfirmDeliveryTransactionHandler()
            ->request($orderTransfer, $orderItems);
    }

    /**
     * Specification:
     * - Performs the cancel payment request to RatePAY Gateway.
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
     * - Performs the refund payment request to RatePAY Gateway.
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
     * - Performs the installment payment method calculator configuration request to RatePAY Gateway.
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
     * - Performs the installment payment method calculator calculation request to RatePAY Gateway.
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
     * - Checks if the payment confirmation API request got success response from RatePAY Gateway.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isPaymentConfirmed(OrderTransfer $orderTransfer)
    {
        return $this
            ->getFactory()
            ->createStatusTransaction()
            ->isPaymentConfirmed($orderTransfer);
    }

    /**
     * Specification:
     * - Checks if the delivery confirmation API request got success response from RatePAY Gateway.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isDeliveryConfirmed(OrderTransfer $orderTransfer)
    {
        return $this
            ->getFactory()
            ->createStatusTransaction()
            ->isDeliveryConfirmed($orderTransfer);
    }

    /**
     * Specification:
     * - Checks if the payment refund API request got success response from RatePAY Gateway.
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
     * - Checks if the payment cancellation API request got success response from RatePAY Gateway.
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
     * - Installs bundle translations to project glossary.
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

    /**
     * Specification:
     * - Retrieves profile data from Ratepay Gateway.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\RatepayProfileResponseTransfer
     */
    public function requestProfile()
    {
        return $this
            ->getFactory()
            ->createRequestProfileTransactionHandler()
            ->request();
    }

}
