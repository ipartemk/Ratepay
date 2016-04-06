<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Api\Converter;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Ratepay\Business\Api\Model\Parts\BankAccount;
use Spryker\Zed\Ratepay\Business\Api\Model\Parts\Customer;
use Spryker\Zed\Ratepay\Business\Api\Model\Parts\Payment;
use Spryker\Zed\Ratepay\Business\Api\Model\Parts\ShoppingBasket;
use Spryker\Zed\Ratepay\Business\Api\Model\Response\ResponseInterface;

interface ConverterInterface
{

    public function mapCustomer(QuoteTransfer $quoteTransfer, $ratepayPaymentTransfer, Customer $customer);

    public function mapBankAccount(QuoteTransfer $quote, BankAccount $bankAccount);

    public function mapPayment(QuoteTransfer $quote, Payment $payment);

    public function mapBasket(QuoteTransfer $quote, ShoppingBasket $basket);

    /**
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Response\ResponseInterface $response
     *
     * @return \Generated\Shared\Transfer\RatepayResponseTransfer
     */
    public function responseToTransferObject(ResponseInterface $response);

}
