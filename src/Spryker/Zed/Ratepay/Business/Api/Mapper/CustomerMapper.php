<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Api\Mapper;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RatepayPaymentElvTransfer;
use Generated\Shared\Transfer\RatepayPaymentInstallmentTransfer;
use Spryker\Zed\Ratepay\Business\Api\Constants as ApiConstants;
use Spryker\Zed\Ratepay\Business\Api\Model\Parts\Customer;

class CustomerMapper extends BaseMapper
{

    /**
     * @var QuoteTransfer
     */
    protected $quoteTransfer;

    /**
     * @var RatepayPaymentElvTransfer|RatepayPaymentInstallmentTransfer
     */
    protected $ratepayPaymentTransfer;

    /**
     * @var Customer
     */
    protected $customer;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Spryker\Shared\Transfer\TransferInterface $ratepayPaymentTransfer
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Parts\Customer $customer
     */
    public function __construct(
        QuoteTransfer $quoteTransfer,
        $ratepayPaymentTransfer,
        Customer $customer
    )
    {
        $this->quoteTransfer = $quoteTransfer;
        $this->ratepayPaymentTransfer = $ratepayPaymentTransfer;
        $this->customer = $customer;
    }

    /**
     * @return void
     */
    public function map()
    {
        /** @var \Generated\Shared\Transfer\CustomerTransfer $ratepayPayment */
        $customerTransfer = $this->quoteTransfer->requireCustomer()->getCustomer();
        /** @var \Generated\Shared\Transfer\AddressTransfer $billingAddress */
        $billingAddress = $this->quoteTransfer->requireBillingAddress()->getBillingAddress();
        /** @var \Generated\Shared\Transfer\AddressTransfer $shippingAddress */
        $shippingAddress = $this->quoteTransfer->requireBillingAddress()->getShippingAddress();

        $this->customer->setAllowCreditInquiry(
                $this->ratepayPaymentTransfer->requireCustomerAllowCreditInquiry()->getCustomerAllowCreditInquiry() ?
                    Customer::ALLOW_CREDIT_INQUIRY_YES : Customer::ALLOW_CREDIT_INQUIRY_NO
            )
            ->setGender($this->ratepayPaymentTransfer->requireGender()->getGender())
            ->setDob($this->ratepayPaymentTransfer->requireDateOfBirth()->getDateOfBirth())
            ->setIpAddress($this->ratepayPaymentTransfer->requireIpAddress()->getIpAddress())
            ->setFirstName($billingAddress->getFirstName())
            ->setLastName($billingAddress->getLastName())
            ->setEmail($customerTransfer->requireEmail()->getEmail())
            ->setPhone($this->quoteTransfer->requireBillingAddress()->getBillingAddress()->requirePhone()->getPhone());

        $addressMapper = new AddressMapper(
            $billingAddress,
            ApiConstants::REQUEST_MODEL_ADDRESS_TYPE_BILLING,
            $this->customer->getBillingAddress()
        );
        $addressMapper->map();

        $addressMapper = new AddressMapper(
            $shippingAddress,
            ApiConstants::REQUEST_MODEL_ADDRESS_TYPE_DELIVERY,
            $this->customer->getShippingAddress()
        );
        $addressMapper->map();
    }

}
