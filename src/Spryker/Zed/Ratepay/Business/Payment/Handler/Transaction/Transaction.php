<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Payment\Handler\Transaction;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Ratepay\Persistence\SpyPaymentRatepayQuery;
use Spryker\Zed\Ratepay\Business\Exception\NoMethodMapperException;
use Spryker\Zed\Ratepay\Business\Payment\Method\MethodInterface;

class Transaction implements TransactionInterface
{

    /**
     * @var array
     */
    protected $methodMappers = [];

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\RatepayResponseTransfer
     */
    public function preCheckPayment(QuoteTransfer $quoteTransfer)
    {
        $paymentMethod = $quoteTransfer->requirePayment()->getPayment()->requirePaymentMethod()->getPaymentMethod();
        return $this
            ->getMethodMapper($paymentMethod)
            ->paymentRequest($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\RatepayResponseTransfer
     */
    public function preAuthorizePayment(OrderTransfer $orderTransfer)
    {
        $paymentMethod = $this->getPaymentMethod($orderTransfer);
        return $this
            ->getMethodMapper($paymentMethod)
            ->paymentConfirm($orderTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\RatepayResponseTransfer
     */
    public function capturePayment(OrderTransfer $orderTransfer)
    {
        $paymentMethod = $this->getPaymentMethod($orderTransfer);
        return $this
            ->getMethodMapper($paymentMethod)
            ->deliveryConfirm($orderTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\PayolutionTransactionResponseTransfer
     */
    public function cancelOrder(OrderTransfer $orderTransfer)
    {
        $paymentMethod = $this->getPaymentMethod($orderTransfer);
        return $this
            ->getMethodMapper($paymentMethod)
            ->cancelOrder($orderTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isPreAuthorizationApproved(OrderTransfer $orderTransfer)
    {
        $paymentMethod = $this->getPaymentMethod($orderTransfer);
        return $this
            ->getMethodMapper($paymentMethod)
            ->isPreAuthorizationApproved($orderTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isCaptureApproved(OrderTransfer $orderTransfer)
    {
        $paymentMethod = $this->getPaymentMethod($orderTransfer);
        return $this
            ->getMethodMapper($paymentMethod)
            ->isCaptureApproved($orderTransfer);
    }

    /**
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isCancellationConfirmed(OrderTransfer $orderTransfer)
    {
        $paymentMethod = $this->getPaymentMethod($orderTransfer);
        return $this
            ->getMethodMapper($paymentMethod)
            ->isCancellationConfirmed($orderTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @return string
     */
    protected function getPaymentMethod(OrderTransfer $orderTransfer)
    {
        $query = new SpyPaymentRatepayQuery();
        $payment = $query->findByFkSalesOrder($orderTransfer->requireIdSalesOrder()->getIdSalesOrder())->getFirst();

        return $payment->getPaymentType();
    }

    /**
     * @param \Spryker\Zed\Ratepay\Business\Payment\Method\MethodInterface $mapper
     *
     * @return void
     */
    public function registerMethodMapper(MethodInterface $mapper)
    {
        $this->methodMappers[$mapper->getMethodName()] = $mapper;
    }

    /**
     * @param string $accountBrand
     *
     * @throws \Spryker\Zed\Ratepay\Business\Exception\NoMethodMapperException
     *
     * @return \Spryker\Zed\Ratepay\Business\Payment\Method\MethodInterface
     */
    protected function getMethodMapper($accountBrand)
    {
        if (isset($this->methodMappers[$accountBrand]) === false) {
            throw new NoMethodMapperException('The method mapper is not registered.');
        }

        return $this->methodMappers[$accountBrand];
    }

}
