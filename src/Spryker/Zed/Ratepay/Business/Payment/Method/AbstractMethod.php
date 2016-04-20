<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\Ratepay\Business\Payment\Method;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Ratepay\Business\Api\Constants as ApiConstants;
use Spryker\Zed\Ratepay\Business\Api\Converter\ConverterInterface;
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
     * @var \Spryker\Zed\Ratepay\Business\Api\Converter\ConverterInterface
     */
    protected $converter;

    /**
     * @var \Spryker\Zed\Ratepay\Persistence\RatepayQueryContainerInterface $queryContainer
     */
    protected $queryContainer;

    /**
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\RequestModelFactoryInterface $modelFactory
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Spryker\Zed\Ratepay\Business\Api\Converter\ConverterInterface $converter
     * @param \Spryker\Zed\Ratepay\Persistence\RatepayQueryContainerInterface $queryContainer
     *
     */
    public function __construct(
        RequestModelFactoryInterface $modelFactory,
        ConverterInterface $converter,
        RatepayQueryContainerInterface $queryContainer
    ) {
        $this->modelFactory = $modelFactory;
        $this->converter = $converter;
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
        $this->converter->mapPayment($quoteTransfer, $paymentData, $request->getPayment());
        $this->converter->mapCustomer($quoteTransfer, $paymentData, $request->getCustomer());
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
        /** @var \Spryker\Zed\Ratepay\Business\Api\Model\Parts\BankAccount $bankAccount */
        $bankAccount = $this->modelFactory->build(ApiConstants::REQUEST_MODEL_BANK_ACCOUNT);
        $request->getCustomer()->setBankAccount($bankAccount);
        $this->converter->mapBankAccount($quoteTransfer, $paymentData, $request->getCustomer()->getBankAccount());
    }

    /**
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Request
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
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Request
     */
    public function deliveryConfirm(OrderTransfer $orderTransfer)
    {
        $payment = $this->loadOrderPayment($orderTransfer);
        $paymentData = $this->getTransferObjectFromPayment($payment);

        /**
         * @var \Spryker\Zed\Ratepay\Business\Api\Model\Deliver\Confirm $request
         */
        $request = $this->modelFactory->build(ApiConstants::REQUEST_MODEL_DELIVER_CONFIRM);
        $request->getHead()->setTransactionId($payment->getTransactionId())->setTransactionShortId($payment->getTransactionShortId());
        $this->mapShoppingBasketAndItems($orderTransfer, $paymentData, $request);

        return $request;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Request
     */
    public function paymentCancel(OrderTransfer $orderTransfer)
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
        $this->mapShoppingBasketAndItems($orderTransfer, $paymentData, $request);

        return $request;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Request
     */
    public function paymentRefund(OrderTransfer $orderTransfer)
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
        $this->mapShoppingBasketAndItems($orderTransfer, $paymentData, $request);

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
        $this->converter->mapBasket($dataTransfer, $paymentData, $request->getShoppingBasket());
        $basketItems = $dataTransfer->requireItems()->getItems();
        foreach ($basketItems as $basketItem) {
            $shoppingBasketItem = $this->modelFactory->build(ApiConstants::REQUEST_MODEL_BASKET_ITEM);
            $this->converter->mapBasketItem($basketItem, $shoppingBasketItem);
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
