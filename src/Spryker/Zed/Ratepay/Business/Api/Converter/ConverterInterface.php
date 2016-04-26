<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Api\Converter;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Ratepay\Business\Api\Model\Parts\Address;
use Spryker\Zed\Ratepay\Business\Api\Model\Parts\BankAccount;
use Spryker\Zed\Ratepay\Business\Api\Model\Parts\Customer;
use Spryker\Zed\Ratepay\Business\Api\Model\Parts\InstallmentCalculation;
use Spryker\Zed\Ratepay\Business\Api\Model\Parts\InstallmentDetail;
use Spryker\Zed\Ratepay\Business\Api\Model\Parts\Payment;
use Spryker\Zed\Ratepay\Business\Api\Model\Parts\ShoppingBasket;
use Spryker\Zed\Ratepay\Business\Api\Model\Parts\ShoppingBasketItem;
use Spryker\Zed\Ratepay\Business\Api\Model\Payment\Calculation;
use Spryker\Zed\Ratepay\Business\Api\Model\Response\CalculationResponse;
use Spryker\Zed\Ratepay\Business\Api\Model\Response\ConfigurationResponse;
use Spryker\Zed\Ratepay\Business\Api\Model\Response\ResponseInterface;

interface ConverterInterface
{

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Spryker\Shared\Transfer\TransferInterface $ratepayPaymentTransfer
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Parts\Customer $customer
     *
     * @return void
     */
    public function mapCustomer(QuoteTransfer $quoteTransfer, $ratepayPaymentTransfer, Customer $customer);

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     * @param string $type
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Parts\Address $address
     *
     * @return void
     */
    public function mapAddress(AddressTransfer $addressTransfer, $type, Address $address);

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quote
     * @param \Spryker\Shared\Transfer\TransferInterface $ratepayPaymentTransfer
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Parts\BankAccount $bankAccount
     *
     * @return void
     */
    public function mapBankAccount(QuoteTransfer $quote, $ratepayPaymentTransfer, BankAccount $bankAccount);

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quote
     * @param \Spryker\Shared\Transfer\TransferInterface $ratepayPaymentTransfer
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Parts\Payment $payment
     *
     * @return void
     */
    public function mapPayment(QuoteTransfer $quote, $ratepayPaymentTransfer, Payment $payment);

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quote
     * @param \Spryker\Shared\Transfer\TransferInterface $ratepayPaymentTransfer
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Parts\ShoppingBasket $basket
     *
     * @return void
     */
    public function mapBasket($quote, $ratepayPaymentTransfer, ShoppingBasket $basket);

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $quoteTransfer
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Parts\ShoppingBasketItem $basketItem
     *
     * @return void
     */
    public function mapBasketItem(ItemTransfer $quoteTransfer, ShoppingBasketItem $basketItem);

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\RatepayPaymentInstallmentTransfer $ratepayPaymentTransfer
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Parts\InstallmentCalculation $calculation
     *
     * @return void
     */
    public function mapInstallmentCalculation(QuoteTransfer $quoteTransfer, $ratepayPaymentTransfer, InstallmentCalculation $calculation);

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\RatepayPaymentInstallmentTransfer $ratepayPaymentTransfer
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Parts\InstallmentDetail $installmentDetail
     *
     * @return void
     */
    public function mapInstallmentDetail(QuoteTransfer $quoteTransfer, $ratepayPaymentTransfer, InstallmentDetail $installmentDetail);

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quote
     * @param \Spryker\Shared\Transfer\TransferInterface $ratepayPaymentTransfer
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Parts\Payment $payment
     *
     * @return void
     */
    public function mapInstallmentPayment(QuoteTransfer $quote, $ratepayPaymentTransfer, Payment $payment);

    /**
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Response\ResponseInterface $response
     *
     * @return \Generated\Shared\Transfer\RatepayResponseTransfer
     */
    public function responseToTransferObject(ResponseInterface $response);

    /**
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Response\ConfigurationResponse $response
     *
     * @return \Generated\Shared\Transfer\RatepayInstallmentConfigurationResponseTransfer
     */
    public function responseToInstallmentConfigurationResponseObject(ConfigurationResponse $response);

    /**
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Response\CalculationResponse $response
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Calculation $request
     *
     * @return \Generated\Shared\Transfer\RatepayInstallmentCalculationResponseTransfer
     */
    public function responseToInstallmentCalculationResponseObject(CalculationResponse $response, Calculation $request);

}
