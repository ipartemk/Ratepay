<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\Ratepay\Business\Api\Converter;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
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
     * @param \Spryker\Shared\Transfer\AbstractTransfer $ratepayPaymentTransfer
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Parts\Customer $customer
     *
     * @return void
     */
    public function mapCustomer(QuoteTransfer $quoteTransfer, $ratepayPaymentTransfer, Customer $customer)
    {
        /** @var \Generated\Shared\Transfer\CustomerTransfer $ratepayPayment */
        $customerTransfer = $quoteTransfer->requireCustomer()->getCustomer();
        /** @var \Generated\Shared\Transfer\AddressTransfer $billingAddress */
        $billingAddress = $quoteTransfer->requireBillingAddress()->getBillingAddress();
        $customer
            ->setAllowCreditInquiry(
                $ratepayPaymentTransfer->requireCustomerAllowCreditInquiry()->getCustomerAllowCreditInquiry() ?
                    Customer::ALLOW_CREDIT_INQUIRY_YES : Customer::ALLOW_CREDIT_INQUIRY_NO
            )
            ->setGender($ratepayPaymentTransfer->requireGender()->getGender())
            ->setDob($ratepayPaymentTransfer->requireDateOfBirth()->getDateOfBirth())
            ->setIpAddress($ratepayPaymentTransfer->requireIpAddress()->getIpAddress())
            ->setFirstName($billingAddress->getFirstName())
            ->setLastName($billingAddress->getLastName())
            ->setEmail($customerTransfer->requireEmail()->getEmail())
            ->setPhone($quoteTransfer->requireBillingAddress()->getBillingAddress()->requirePhone()->getPhone());

        $this->mapAddress(
            $billingAddress,
            ApiConstants::REQUEST_MODEL_ADDRESS_TYPE_BILLING,
            $customer->getBillingAddress()
        );
        $this->mapAddress(
            $quoteTransfer->requireShippingAddress()->getShippingAddress(),
            ApiConstants::REQUEST_MODEL_ADDRESS_TYPE_DELIVERY,
            $customer->getShippingAddress()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     * @param string $type
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Parts\Address $address
     *
     * @return void
     */
    public function mapAddress(AddressTransfer $addressTransfer, $type, Address $address)
    {
        $address->setAddressType($type)
            ->setCity($addressTransfer->requireCity()->getCity())
            ->setCountryCode($addressTransfer->requireIso2Code()->getIso2Code())
            ->setStreet($addressTransfer->requireAddress1()->getAddress1())
            ->setStreetAdditional($addressTransfer->getAddress3()) // This is optional.
            ->setStreetNumber($addressTransfer->requireAddress2()->getAddress2())
            ->setZipCode($addressTransfer->requireZipCode()->getZipCode())
        ;
        if ($type != ApiConstants::REQUEST_MODEL_ADDRESS_TYPE_BILLING) {
            $address
                ->setFirstName($addressTransfer->requireFirstName()->getFirstName())
                ->setLastName($addressTransfer->requireLastName()->getLastName())
            ;
        }
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Spryker\Shared\Transfer\TransferInterface $ratepayPaymentTransfer
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Parts\BankAccount $bankAccount
     *
     * @return void
     */
    public function mapBankAccount(QuoteTransfer $quoteTransfer, $ratepayPaymentTransfer, BankAccount $bankAccount)
    {
        $bankAccount->setOwner($ratepayPaymentTransfer->getBankAccountHolder());
        $bankAccount->setIban($ratepayPaymentTransfer->getBankAccountIban());
        $bankAccount->setBicSwift($ratepayPaymentTransfer->getBankAccountBic());
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Spryker\Shared\Transfer\TransferInterface $ratepayPaymentTransfer
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Parts\Payment $payment
     *
     * @return void
     */
    public function mapPayment(QuoteTransfer $quoteTransfer, $ratepayPaymentTransfer, Payment $payment)
    {
        $totalsTransfer = $quoteTransfer->requireTotals()->getTotals();

        $payment->setCurrency($ratepayPaymentTransfer->requireCurrencyIso3()->getCurrencyIso3());
        $payment->setMethod($ratepayPaymentTransfer->requirePaymentType()->getPaymentType());

        $grandTotal = $this->centsToDecimal($totalsTransfer->requireGrandTotal()->getGrandTotal());
        $payment->setAmount($grandTotal);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Spryker\Shared\Transfer\TransferInterface $ratepayPaymentTransfer
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Parts\ShoppingBasket $basket
     *
     * @return void
     */
    public function mapBasket($quoteTransfer, $ratepayPaymentTransfer, ShoppingBasket $basket)
    {
        $totalsTransfer = $quoteTransfer->requireTotals()->getTotals();

        $grandTotal = $this->centsToDecimal($totalsTransfer->requireGrandTotal()->getGrandTotal());
        $basket->setAmount($grandTotal);
        $basket->setCurrency($ratepayPaymentTransfer->requireCurrencyIso3()->getCurrencyIso3());

        $shippingUnitPrice = $this->centsToDecimal($totalsTransfer->requireExpenseTotal()->getExpenseTotal());
        $basket->setShippingUnitPrice($shippingUnitPrice);

        $discountUnitPrice = $this->centsToDecimal($totalsTransfer->requireDiscountTotal()->getDiscountTotal());
        $basket->setDiscountUnitPrice($discountUnitPrice);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Parts\ShoppingBasketItem $basketItem
     *
     * @return void
     */
    public function mapBasketItem(ItemTransfer $itemTransfer, ShoppingBasketItem $basketItem)
    {
        $basketItem->setItemName($itemTransfer->requireName()->getName());
        $basketItem->setArticleNumber($itemTransfer->requireSku()->getSku());
        $basketItem->setUniqueArticleNumber($itemTransfer->requireGroupKey()->getGroupKey());
        $basketItem->setQuantity($itemTransfer->requireQuantity()->getQuantity());
        $basketItem->setTaxRate($itemTransfer->requireTaxRate()->getTaxRate());
        $basketItem->setDescription($itemTransfer->getDescription());
        $basketItem->setDescriptionAddition($itemTransfer->getDescriptionAddition());

        $itemPrice = $this->centsToDecimal($itemTransfer->requireUnitGrossPriceWithProductOptions()->getUnitGrossPriceWithProductOptions());
        $basketItem->setUnitPriceGross($itemPrice);

        //todo:: uncomment discount amount when discount per item will be implemented.
        //$itemDiscount = $this->centsToDecimal($itemTransfer->requireUnitTotalDiscountAmountWithProductOption()->getUnitTotalDiscountAmountWithProductOption());
        //$basketItem->setDiscount($itemDiscount);

        // @todo: ProductOptions didn't tested, because we have no implementation for it now.
        foreach ($itemTransfer->getProductOptions() as $productOption) {
            $basketItem->addProductOption($productOption->getLabelOptionValue());
        }
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
            ->setResultText($response->getResultText())
            ->setCustomerMessage($response->getCustomerMessage())
            ->setPaymentMethod($response->getPaymentMethod());

        return $responseTransfer;
    }

    /**
     * @param int $amount
     *
     * @return float
     */
    protected function centsToDecimal($amount)
    {
        return CurrencyManager::getInstance()->convertCentToDecimal($amount);
    }

}
