<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Payment\Handler\Transaction;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Ratepay\Business\Exception\NoMethodMapperException;
use Spryker\Zed\Ratepay\Business\Payment\Method\MethodInterface;
use Orm\Zed\Ratepay\Persistence\SpyPaymentRatepayQuery;

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
        $query = new SpyPaymentRatepayQuery();
        $payment = $query->findByFkSalesOrder($orderTransfer->requireIdSalesOrder()->getIdSalesOrder())->getFirst();

        $paymentMethod = $orderTransfer->requirePayment()->getPayment()->requirePaymentMethod()->getPaymentMethod();
        return $this
            ->getMethodMapper($paymentMethod)
            ->paymentRequest($orderTransfer);
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
