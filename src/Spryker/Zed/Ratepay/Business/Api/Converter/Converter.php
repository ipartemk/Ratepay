<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Api\Converter;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\RatepayResponseTransfer;
use Spryker\Shared\Library\Currency\CurrencyManager;
use Spryker\Zed\Ratepay\Business\Api\Constants as ApiConstants;
use Spryker\Zed\Ratepay\Business\Api\Model\Parts\Address;
use Spryker\Zed\Ratepay\Business\Api\Model\Parts\BankAccount;
use Spryker\Zed\Ratepay\Business\Api\Model\Parts\Customer;
use Spryker\Zed\Ratepay\Business\Api\Model\Parts\Payment;
use Spryker\Zed\Ratepay\Business\Api\Model\Parts\ShoppingBasket;
use Spryker\Zed\Ratepay\Business\Api\Model\Parts\ShoppingBasketItem;
use Spryker\Zed\Ratepay\Business\Api\Model\Response\ResponseInterface;

class Converter implements ConverterInterface
{

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\RatepayPaymentInvoiceTransfer $ratepayPaymentTransfer
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Parts\Customer $customer
     *
     * @return void
     */
    public function mapCustomer(QuoteTransfer $quoteTransfer, $ratepayPaymentTransfer, Customer $customer)
    {
        /** @var \Generated\Shared\Transfer\RatepayPaymentInvoiceTransfer $ratepayPayment */
        $customerTransfer = $quoteTransfer->requireCustomer()->getCustomer();
        $customer
            ->setAllowCreditInquiry($ratepayPaymentTransfer->requireCustomerAllowCreditInquiry()->getCustomerAllowCreditInquiry() ? 'yes' : 'no')
            ->setGender($ratepayPaymentTransfer->requireGender()->getGender())
            ->setDob($ratepayPaymentTransfer->requireDateOfBirth()->getDateOfBirth())
            ->setIpAddress($ratepayPaymentTransfer->requireIpAddress()->getIpAddress())
            ->setFirstName($customerTransfer->requireFirstName()->getFirstName())
            ->setLastName($customerTransfer->requireLastName()->getLastName())
            ->setEmail($customerTransfer->requireEmail()->getEmail())
            ->setPhone($quoteTransfer->requireBillingAddress()->getBillingAddress()->requirePhone()->getPhone());

        $this->mapAddress($quoteTransfer->requireBillingAddress()->getBillingAddress(), ApiConstants::REQUEST_MODEL_ADDRESS_TYPE_BILLING, $customer->getBillingAddress());
        $this->mapAddress($quoteTransfer->requireShippingAddress()->getShippingAddress(), ApiConstants::REQUEST_MODEL_ADDRESS_TYPE_SHIPPING, $customer->getShippingAddress());
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     * @param string $type
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Parts\Address $address
     * @return void
     */
    public function mapAddress(AddressTransfer $addressTransfer, $type, Address $address)
    {
        $address->setAddressType($type)
            ->setFirstName($addressTransfer->requireFirstName()->getFirstName())
            ->setLastName($addressTransfer->requireLastName()->getLastName())
            ->setCity($addressTransfer->requireCity()->getCity())
            ->setCountryCode($addressTransfer->requireIso2Code()->getIso2Code())
            ->setStreet($addressTransfer->requireAddress1()->getAddress1())
            ->setStreetAdditional($addressTransfer->getAddress3()) // This is optional.
            ->setStreetNumber($addressTransfer->requireAddress2()->getAddress2())
            ->setZipCode($addressTransfer->requireZipCode()->getZipCode());
    }

    public function mapBankAccount(QuoteTransfer $quoteTransfer, $ratepayPaymentTransfer, BankAccount $bankAccount)
    {
        $bankAccount->setOwner($ratepayPaymentTransfer->getBankAccountHolder());
        $bankAccount->setIban($ratepayPaymentTransfer->getBankAccountIban());
        $bankAccount->setBicSwift($ratepayPaymentTransfer->getBankAccountBic());
    }

    public function mapPayment(QuoteTransfer $quoteTransfer, Payment $payment)
    {

    }

    public function mapBasket(QuoteTransfer $quoteTransfer, $ratepayPaymentTransfer, ShoppingBasket $basket)
    {
        //todo: get correct transfer objects.
        $payment = $quoteTransfer->requirePayment()->getPayment();
        $items = $quoteTransfer->getItems();

        foreach ($items as $item) {
            $item = $item;
        }
        
        $basket->setAmount($quoteTransfer->getInstallmentTotalAmount());
        $basket->setCurrency($ratepayPaymentTransfer->getCurrencyIso3());
        $basket->setItems(count($items));
    }

    public function mapBasketItem(ItemTransfer $itemTransfer, ShoppingBasketItem $basketItem)
    {
        $basketItem->setArticleNumber($itemTransfer->getSku());
        $basketItem->setUniqueArticleNumber($itemTransfer->getGroupKey());
        $basketItem->setQuantity($itemTransfer->getQuantity());
        $basketItem->setTaxRate($itemTransfer->getTaxRate());
        $basketItem->setUnitPriceGross($itemTransfer->getUnitGrossPrice());
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
