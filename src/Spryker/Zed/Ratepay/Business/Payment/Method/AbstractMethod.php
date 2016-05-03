<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Payment\Method;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Ratepay\Business\Api\Constants as ApiConstants;
use Spryker\Zed\Ratepay\Business\Api\Mapper\MapperFactory;
use Spryker\Zed\Ratepay\Business\Api\Model\RequestModelFactoryInterface;
use \Spryker\Zed\Ratepay\Persistence\RatepayQueryContainerInterface;

abstract class AbstractMethod implements MethodInterface
{

    /**
     * @var \Spryker\Zed\Ratepay\Business\Api\Adapter\AdapterInterface
     */
    protected $adapter;

    /**
     * @var \Spryker\Zed\Ratepay\Business\Api\Model\RequestModelFactoryInterface
     */
    protected $modelFactory;

    /**
     * @var \Spryker\Zed\Ratepay\Business\Api\Mapper\MapperFactory
     */
    protected $mapperFactory;

    /**
     * @var \Spryker\Zed\Ratepay\Persistence\RatepayQueryContainerInterface $queryContainer
     */
    protected $queryContainer;

    /**
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\RequestModelFactoryInterface $modelFactory
     * @param \Spryker\Zed\Ratepay\Business\Api\Mapper\MapperFactory $mapperFactory
     * @param \Spryker\Zed\Ratepay\Persistence\RatepayQueryContainerInterface $queryContainer
     *
     */
    public function __construct(
        RequestModelFactoryInterface $modelFactory,
        MapperFactory $mapperFactory,
        RatepayQueryContainerInterface $queryContainer
    ) {
        $this->modelFactory = $modelFactory;
        $this->mapperFactory = $mapperFactory;
        $this->queryContainer = $queryContainer;
    }

    /**
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Init
     */
    public function paymentInit()
    {
        return $this->modelFactory->build(ApiConstants::REQUEST_MODEL_PAYMENT_INIT);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Request
     */
    public function paymentRequest($quoteTransfer)
    {
        $paymentData = $this->getPaymentData($quoteTransfer);

        /**
         * @var \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Request $request
         */
        $request = $this->modelFactory->build(ApiConstants::REQUEST_MODEL_PAYMENT_REQUEST);
        $this->mapPaymentData($quoteTransfer, $paymentData, $request);

        return $request;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Spryker\Shared\Transfer\TransferInterface $paymentData
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Request $request
     *
     * @return void
     */
    protected function mapPaymentData($quoteTransfer, $paymentData, $request)
    {
        $request->getHead()
            ->setTransactionId($paymentData->getTransactionId())->setTransactionShortId($paymentData->getTransactionShortId())
            ->setCustomerId($quoteTransfer->getCustomer()->getIdCustomer())
            ->setDeviceFingerprint($paymentData->requireDeviceFingerprint()->getDeviceFingerprint());
        $this->mapperFactory
            ->getPaymentMapper($quoteTransfer, $paymentData, $request->getPayment())
            ->map();
        $this->mapperFactory
            ->getCustomerMapper($quoteTransfer, $paymentData, $request->getCustomer())
            ->map();
        $this->mapShoppingBasketAndItems($quoteTransfer, $paymentData, $request);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\RatepayPaymentElvTransfer $paymentData
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Request $request
     *
     * @return void
     */
    protected function mapBankAccountData($quoteTransfer, $paymentData, $request)
    {
        $bankAccount = $this->modelFactory->build(ApiConstants::REQUEST_MODEL_BANK_ACCOUNT);
        $request->getCustomer()->setBankAccount($bankAccount);
        $this->mapperFactory
            ->getBankAccountMapper($quoteTransfer, $paymentData, $request->getCustomer()->getBankAccount())
            ->map();
    }

    /**
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Confirm
     */
    public function paymentConfirm(OrderTransfer $orderTransfer)
    {
        $payment = $this->loadOrderPayment($orderTransfer);

        /**
         * @var \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Confirm $request
         */
        $request = $this->modelFactory->build(ApiConstants::REQUEST_MODEL_PAYMENT_CONFIRM);
        $request->getHead()->setTransactionId($payment->getTransactionId())->setTransactionShortId($payment->getTransactionShortId());
        $request->getHead()->setOrderId($orderTransfer->requireOrderReference()->getIdSalesOrder());
        $request->getHead()->setExternalOrderId($orderTransfer->requireOrderReference()->getOrderReference());

        return $request;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer[] $orderItems
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Request
     */
    public function deliveryConfirm(OrderTransfer $orderTransfer, array $orderItems)
    {
        $payment = $this->loadOrderPayment($orderTransfer);
        $paymentData = $this->getTransferObjectFromPayment($payment);

        /**
         * @var \Spryker\Zed\Ratepay\Business\Api\Model\Deliver\Confirm $request
         */
        $request = $this->modelFactory->build(ApiConstants::REQUEST_MODEL_DELIVER_CONFIRM);
        $request->getHead()->setTransactionId($payment->getTransactionId())->setTransactionShortId($payment->getTransactionShortId());
        $this->mapPartialShoppingBasketAndItems($orderTransfer, $paymentData, $request, $orderItems);

        return $request;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer[] $orderItems
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Request
     */
    public function paymentCancel(OrderTransfer $orderTransfer, array $orderItems)
    {
        $payment = $this->loadOrderPayment($orderTransfer);
        $paymentData = $this->getTransferObjectFromPayment($payment);

        /**
         * @var \Spryker\Zed\Ratepay\Business\Api\Model\Deliver\Confirm $request
         */
        $request = $this->modelFactory->build(ApiConstants::REQUEST_MODEL_PAYMENT_CANCEL);
        $request->getHead()->setTransactionId($payment->getTransactionId())->setTransactionShortId($payment->getTransactionShortId());
        $request->getHead()->setOrderId($orderTransfer->requireOrderReference()->getIdSalesOrder());
        $request->getHead()->setExternalOrderId($orderTransfer->requireOrderReference()->getOrderReference());
        $this->mapPartialShoppingBasketAndItems($orderTransfer, $paymentData, $request, $orderItems);

        return $request;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer[] $orderItems
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Request
     */
    public function paymentRefund(OrderTransfer $orderTransfer, array $orderItems)
    {
        $payment = $this->loadOrderPayment($orderTransfer);
        $paymentData = $this->getTransferObjectFromPayment($payment);

        /**
         * @var \Spryker\Zed\Ratepay\Business\Api\Model\Deliver\Confirm $request
         */
        $request = $this->modelFactory->build(ApiConstants::REQUEST_MODEL_PAYMENT_REFUND);
        $request->getHead()->setTransactionId($payment->getTransactionId())->setTransactionShortId($payment->getTransactionShortId());
        $request->getHead()->setOrderId($orderTransfer->requireOrderReference()->getIdSalesOrder());
        $request->getHead()->setExternalOrderId($orderTransfer->requireOrderReference()->getOrderReference());
        $this->mapPartialShoppingBasketAndItems($orderTransfer, $paymentData, $request, $orderItems);

        return $request;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Request
     */
    public function configurationRequest(QuoteTransfer $quoteTransfer)
    {

    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Request
     */
    public function calculationRequest(QuoteTransfer $quoteTransfer)
    {

    }

    /**
     * @param \Orm\Zed\Ratepay\Persistence\SpyPaymentRatepay $payment
     *
     * @return mixed
     */
    protected function getTransferObjectFromPayment($payment)
    {
        $paymentTransfer = $this->getPaymentTransferObject($payment);
        $paymentTransfer->fromArray($payment->toArray(), true);

        return $paymentTransfer;
    }

    /**
     * @param \Orm\Zed\Ratepay\Persistence\SpyPaymentRatepay $payment
     *
     * @return mixed
     */
    abstract protected function getPaymentTransferObject($payment);

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer|\Generated\Shared\Transfer\QuoteTransfer $dataTransfer
     * @param \Spryker\Shared\Transfer\TransferInterface $paymentData
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Request $request
     *
     * @return void
     */
    protected function mapShoppingBasketAndItems($dataTransfer, $paymentData, $request)
    {
        $this->mapperFactory
            ->getBasketMapper($dataTransfer, $paymentData, $request->getShoppingBasket())
            ->map();
        $basketItems = $dataTransfer->requireItems()->getItems();
        foreach ($basketItems as $basketItem) {
            $shoppingBasketItem = $this->modelFactory->build(ApiConstants::REQUEST_MODEL_BASKET_ITEM);
            $this->mapperFactory
                ->getBasketItemMapper($basketItem, $shoppingBasketItem)
                ->map();

            $request->getShoppingBasket()->addItem($shoppingBasketItem);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer|\Generated\Shared\Transfer\QuoteTransfer $dataTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer[] $orderItems
     * @param \Spryker\Shared\Transfer\TransferInterface $paymentData
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Request $request
     *
     * @return void
     */
    protected function mapPartialShoppingBasketAndItems($dataTransfer, $paymentData, $request, array $orderItems)
    {
        $this->mapperFactory
            ->getPartialBasketMapper($dataTransfer, $paymentData, $orderItems, $request->getShoppingBasket())
            ->map();

        foreach ($orderItems as $basketItem) {
            $shoppingBasketItem = $this->modelFactory->build(ApiConstants::REQUEST_MODEL_BASKET_ITEM);
            $this->mapperFactory
                ->getBasketItemMapper($basketItem, $shoppingBasketItem)
                ->map();

            $request->getShoppingBasket()->addItem($shoppingBasketItem);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Orm\Zed\Ratepay\Persistence\SpyPaymentRatepay
     */
    protected function loadOrderPayment(OrderTransfer $orderTransfer)
    {
        return $this->queryContainer
            ->queryPayments()
            ->findByFkSalesOrder(
                $orderTransfer->requireIdSalesOrder()->getIdSalesOrder()
            )->getFirst();
    }

}
