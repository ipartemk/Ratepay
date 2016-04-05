<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Payment\Handler\Transaction;

use Generated\Shared\Transfer\QuoteTransfer;
//use Spryker\Zed\Ratepay\Business\Api\Converter\ConverterInterface;
use Psr\Log\LoggerInterface;
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
     * @return \Generated\Shared\Transfer\PayolutionTransactionResponseTransfer
     */
    public function preCheckPayment(QuoteTransfer $quoteTransfer)
    {
        $paymentMethod = $quoteTransfer->getPayment()->getPaymentMethod();
        $requestData = $this
            ->getMethodMapper($paymentMethod)
            ->paymentRequest($quoteTransfer);

        //return $this->sendRequest($requestData);
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
