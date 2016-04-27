<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Api\Mapper;

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

class MapperFactory
{

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     * @param string $type
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Parts\Address $address
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Mapper\AddressMapper
     */
    public function getAddressMapper(
        AddressTransfer $addressTransfer,
        $type,
        Address $address
    )
    {
        return new AddressMapper(
            $addressTransfer,
            $type,
            $address
        );
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Spryker\Shared\Transfer\TransferInterface $ratepayPaymentTransfer
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Parts\BankAccount $bankAccount
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Mapper\BankAccountMapper
     */
    public function getBankAccountMapper(
        QuoteTransfer $quoteTransfer,
        $ratepayPaymentTransfer,
        BankAccount $bankAccount
    )
    {
        return new BankAccountMapper(
            $quoteTransfer,
            $ratepayPaymentTransfer,
            $bankAccount
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Parts\ShoppingBasketItem $basketItem
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Mapper\BasketItemMapper
     */
    public function getBasketItemMapper(
        ItemTransfer $itemTransfer,
        ShoppingBasketItem $basketItem
    )
    {
        return new BasketItemMapper(
            $itemTransfer,
            $basketItem
        );
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Spryker\Shared\Transfer\TransferInterface $ratepayPaymentTransfer
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Parts\ShoppingBasket $basket
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Mapper\BasketMapper
     */
    public function getBasketMapper(
        $quoteTransfer,
        $ratepayPaymentTransfer,
        ShoppingBasket $basket
    )
    {
        return new BasketMapper(
            $quoteTransfer,
            $ratepayPaymentTransfer,
            $basket
        );
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Spryker\Shared\Transfer\TransferInterface $ratepayPaymentTransfer
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Parts\Customer $customer
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Mapper\CustomerMapper
     */
    public function getCustomerMapper(
        QuoteTransfer $quoteTransfer,
        $ratepayPaymentTransfer,
        Customer $customer
    )
    {
        return new CustomerMapper(
            $quoteTransfer,
            $ratepayPaymentTransfer,
            $customer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Spryker\Shared\Transfer\TransferInterface $ratepayPaymentTransfer
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Parts\Payment $payment
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Mapper\PaymentMapper
     */
    public function getPaymentMapper(
        QuoteTransfer $quoteTransfer,
        $ratepayPaymentTransfer,
        Payment $payment
    )
    {
        return new PaymentMapper(
            $quoteTransfer,
            $ratepayPaymentTransfer,
            $payment
        );
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\RatepayPaymentInstallmentTransfer $ratepayPaymentTransfer
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Parts\InstallmentCalculation $calculation
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Mapper\InstallmentCalculationMapper
     */
    public function getInstallmentCalculationMapper(
        QuoteTransfer $quoteTransfer,
        $ratepayPaymentTransfer,
        InstallmentCalculation $calculation
    )
    {
        return new InstallmentCalculationMapper(
            $quoteTransfer,
            $ratepayPaymentTransfer,
            $calculation
        );
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\RatepayPaymentInstallmentTransfer $ratepayPaymentTransfer
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Parts\InstallmentDetail $installmentDetail
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Mapper\InstallmentDetailMapper
     */
    public function getInstallmentDetailMapper(
        QuoteTransfer $quoteTransfer,
        $ratepayPaymentTransfer,
        InstallmentDetail $installmentDetail
    )
    {
        return new InstallmentDetailMapper(
            $quoteTransfer,
            $ratepayPaymentTransfer,
            $installmentDetail
        );
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\RatepayPaymentInstallmentTransfer $ratepayPaymentTransfer
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Parts\Payment $payment
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Mapper\InstallmentPaymentMapper
     */
    public function getInstallmentPaymentMapper(
        QuoteTransfer $quoteTransfer,
        $ratepayPaymentTransfer,
        Payment $payment
    )
    {
        return new InstallmentPaymentMapper(
            $quoteTransfer,
            $ratepayPaymentTransfer,
            $payment
        );
    }

}
