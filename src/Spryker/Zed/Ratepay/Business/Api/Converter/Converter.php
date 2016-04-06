<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Api\Converter;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Library\Currency\CurrencyManager;
use Spryker\Zed\Ratepay\Business\Api\Model\Parts\Customer;
use Spryker\Zed\Ratepay\Business\Api\Model\Parts\Payment;
use Spryker\Zed\Ratepay\Business\Api\Model\Parts\ShoppingBasket;

class Converter implements ConverterInterface
{

    public function mapCustomer(QuoteTransfer $quoteTransfer, Customer $customer)
    {
        $customer = $quoteTransfer->requireCustomer()->getCustomer();
        $payment = $quoteTransfer->requirePayment()->getPayment();

    }

    public function mapBankAccount(QuoteTransfer $quoteTransfer, BankAccount $bankAccount)
    {
        $bankAccount->setOwner($quoteTransfer->getBankAccountHolder());
        $bankAccount->setIban($quoteTransfer->getBankAccountIban());
        $bankAccount->setBicSwift($quoteTransfer->getBankAccountBic());
    }

    public function mapPayment(QuoteTransfer $quoteTransfer, Payment $payment)
    {

    }

    public function mapBasket(QuoteTransfer $quoteTransfer, ShoppingBasket $basket)
    {
    }

    public function responseToTransfer()
    {

    }

    /**
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Response\ResponseInterface $response
     *
     * @return \Generated\Shared\Transfer\RatepayResponseTransfer
     */
    public function responseToTransferObject(ResponseInterface $response)
    {
        $responseTransfer = new RatepayResponseTransfer();
        $responseTransfer
            ->setTransactionId($response->getTransactionId())
            ->setTransactionShortId($response->getTransactionShortId())
            ->setSuccessful($response->isSuccessful())
            ->setReasonCode($response->getReasonCode())
            ->setReasonText($response->getReasonText())
            ->setStatusCode($response->getStatusCode())
            ->setStatusText($response->getStatusText())
            ->setResultCode($response->getResultCode())
            ->setResultText($response->getResultText());

        return $responseTransfer;
    }

    /**
     * @param float $amount
     *
     * @return int
     */
    protected function centsToDecimal($amount)
    {
        return CurrencyManager::getInstance()->convertDecimalToCent($amount);
    }

}
