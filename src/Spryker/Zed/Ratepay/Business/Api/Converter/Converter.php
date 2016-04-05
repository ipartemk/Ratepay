<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payolution\Business\Api\Converter;

use Spryker\Shared\Library\Currency\CurrencyManager;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Ratepay\Business\Api\Model\Parts\Customer;
use Spryker\Zed\Ratepay\Business\Api\Model\Parts\Payment;
use Spryker\Zed\Ratepay\Business\Api\Model\Parts\ShoppingBasket;

class Converter implements ConverterInterface
{

    public function mapCustomer(QuoteTransfer $quote, Customer $customer)
    {
    }

    public function mapBankAccount()
    {
    }

    public function mapPayment(QuoteTransfer $quote, Payment $payment)
    {
    }

    public function mapBasket(QuoteTransfer $quote, ShoppingBasket $basket)
    {
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
