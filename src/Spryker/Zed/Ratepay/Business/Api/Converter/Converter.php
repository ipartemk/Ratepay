<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Api\Converter;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RatepayInstallmentCalculationResponseTransfer;
use Generated\Shared\Transfer\RatepayInstallmentConfigurationResponseTransfer;
use Generated\Shared\Transfer\RatepayResponseTransfer;
use Spryker\Shared\Library\Currency\CurrencyManager;
use Spryker\Zed\Ratepay\Business\Api\Constants as ApiConstants;
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

class Converter implements ConverterInterface
{

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\RatepayPaymentElvTransfer $ratepayPaymentTransfer
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
            ->setZipCode($addressTransfer->requireZipCode()->getZipCode());
        if ($type != ApiConstants::REQUEST_MODEL_ADDRESS_TYPE_BILLING) {
            $address
                ->setFirstName($addressTransfer->requireFirstName()->getFirstName())
                ->setLastName($addressTransfer->requireLastName()->getLastName());
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
     * @param \Generated\Shared\Transfer\OrderTransfer|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
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
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\RatepayPaymentInstallmentTransfer $ratepayPaymentTransfer
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Parts\InstallmentCalculation $calculation
     *
     * @return void
     */
    public function mapInstallmentCalculation(QuoteTransfer $quoteTransfer, $ratepayPaymentTransfer, InstallmentCalculation $calculation)
    {
        $grandTotal = $this->centsToDecimal(
            $quoteTransfer->requireTotals()
                ->getTotals()
                ->requireGrandTotal()
                ->getGrandTotal()
        );
        $calculation
            ->setSubType($ratepayPaymentTransfer->getInstallmentCalculationType())
            ->setAmount($grandTotal)
            ->setCalculationRate($ratepayPaymentTransfer->getInstallmentInterestRate())
            ->setMonth($ratepayPaymentTransfer->getInstallmentMonth())
            ->setPaymentFirstday($ratepayPaymentTransfer->getInstallmentPaymentFirstDay())
            ->setCalculationStart($ratepayPaymentTransfer->getInstallmentCalculationStart());
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\RatepayPaymentInstallmentTransfer $ratepayPaymentTransfer
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Parts\InstallmentDetail $installmentDetail
     *
     * @return void
     */
    public function mapInstallmentDetail(QuoteTransfer $quoteTransfer, $ratepayPaymentTransfer, InstallmentDetail $installmentDetail)
    {
        $installmentDetail
            ->setMonthNumber($ratepayPaymentTransfer->getInstallmentMonth())
            ->setAmount($this->centsToDecimal($ratepayPaymentTransfer->getInstallmentRate()))
            ->setLastAmount($this->centsToDecimal($ratepayPaymentTransfer->getInstallmentLastRate()))
            ->setInterestRate($ratepayPaymentTransfer->getInstallmentInterestRate())
            ->setPaymentFirstday($ratepayPaymentTransfer->getInstallmentPaymentFirstDay())
        ;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\RatepayPaymentInstallmentTransfer $ratepayPaymentTransfer
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Parts\Payment $payment
     *
     * @return void
     */
    public function mapInstallmentPayment(QuoteTransfer $quoteTransfer, $ratepayPaymentTransfer, Payment $payment)
    {
        $payment
            ->setDebitPayType($ratepayPaymentTransfer->getDebitPayType())
            ->setInstallmentDetails(new InstallmentDetail())
            ->setAmount(
                $this->centsToDecimal(
                    $quoteTransfer->getPayment()
                        ->getRatepayInstallment()
                        ->getInstallmentGrandTotalAmount()
                )
            )
        ;
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
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Response\ConfigurationResponse $response
     *
     * @return \Generated\Shared\Transfer\RatepayInstallmentConfigurationResponseTransfer
     */
    public function responseToInstallmentConfigurationResponseObject(ConfigurationResponse $response)
    {
        $responseTransfer = new RatepayInstallmentConfigurationResponseTransfer();
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

            ->setInterestrateMin($response->getInterestrateMin())
            ->setInterestrateDefault($response->getInterestrateDefault())
            ->setInterestrateMax($response->getInterestrateMax())
            ->setInterestRateMerchantTowardsBank($response->getInterestRateMerchantTowardsBank())
            ->setMonthNumberMin($response->getMonthNumberMin())
            ->setMonthNumberMax($response->getMonthNumberMax())
            ->setMonthLongrun($response->getMonthLongrun())
            ->setAmountMinLongrun($response->getAmountMinLongrun())
            ->setMonthAllowed($response->getMonthAllowed())
            ->setValidPaymentFirstdays($response->getValidPaymentFirstdays())
            ->setPaymentFirstday($response->getPaymentFirstday())
            ->setPaymentAmount($response->getPaymentAmount())
            ->setPaymentLastrate($response->getPaymentLastrate())
            ->setRateMinNormal($response->getRateMinNormal())
            ->setRateMinLongrun($response->getRateMinLongrun())
            ->setServiceCharge($response->getServiceCharge())
            ->setMinDifferenceDueday($response->getMinDifferenceDueday());

        return $responseTransfer;
    }

    /**
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Response\CalculationResponse $response
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Calculation $request
     *
     * @return \Generated\Shared\Transfer\RatepayInstallmentCalculationResponseTransfer
     */
    public function responseToInstallmentCalculationResponseObject(CalculationResponse $response, Calculation $request)
    {
        $responseTransfer = new RatepayInstallmentCalculationResponseTransfer();
        $responseTransfer
            ->setBaseResponse($this->responseToTransferObject($response))
            ->setSubtype($request->getInstallmentCalculation()->getSubType())

            ->setTotalAmount($this->decimalToCents($response->getTotalAmount()))
            ->setAmount($this->decimalToCents($response->getAmount()))
            ->setInterestAmount($this->decimalToCents($response->getInterestAmount()))
            ->setServiceCharge($this->decimalToCents($response->getServiceCharge()))
            ->setInterestRate($this->decimalToCents($response->getInterestRate()))
            ->setAnnualPercentageRate($response->getAnnualPercentageRate())
            ->setMonthlyDebitInterest($this->decimalToCents($response->getMonthlyDebitInterest()))
            ->setRate($this->decimalToCents($response->getRate()))
            ->setNumberOfRates($response->getNumberOfRates())
            ->setLastRate($this->decimalToCents($response->getLastRate()))
            ->setPaymentFirstDay($response->getPaymentFirstday());

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

    /**
     * @param float $amount
     *
     * @return int
     */
    protected function decimalToCents($amount)
    {
        return CurrencyManager::getInstance()->convertDecimalToCent($amount);
    }

}
