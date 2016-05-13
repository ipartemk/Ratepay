<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Api\Mapper;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Ratepay\Business\Api\Constants as ApiConstants;

class CustomerMapper extends BaseMapper
{

    /**
     * @var \Generated\Shared\Transfer\QuoteTransfer
     */
    protected $quoteTransfer;

    /**
     * @var \Generated\Shared\Transfer\RatepayPaymentElvTransfer|\Generated\Shared\Transfer\RatepayPaymentInstallmentTransfer
     */
    protected $ratepayPaymentTransfer;

    /**
     * @var \Generated\Shared\Transfer\RatepayRequestTransfer
     */
    protected $requestTransfer;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Spryker\Shared\Transfer\TransferInterface $ratepayPaymentTransfer
     * @param \Generated\Shared\Transfer\RatepayRequestTransfer $requestTransfer
     */
    public function __construct(
        QuoteTransfer $quoteTransfer,
        $ratepayPaymentTransfer,
        $requestTransfer
    ) {

        $this->quoteTransfer = $quoteTransfer;
        $this->ratepayPaymentTransfer = $ratepayPaymentTransfer;
        $this->requestTransfer = $requestTransfer;
    }

    /**
     * @return void
     */
    public function map()
    {
        $customerTransfer = $this->quoteTransfer->requireCustomer()->getCustomer();
        $billingAddress = $this->quoteTransfer->requireBillingAddress()->getBillingAddress();
        $shippingAddress = $this->quoteTransfer->requireBillingAddress()->getShippingAddress();

        $this->requestTransfer->getCustomer()
            ->setAllowCreditInquiry($this->ratepayPaymentTransfer->getCustomerAllowCreditInquiry())
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
            $this->requestTransfer
        );
        $addressMapper->map();

        $addressMapper = new AddressMapper(
            $shippingAddress,
            ApiConstants::REQUEST_MODEL_ADDRESS_TYPE_DELIVERY,
            $this->requestTransfer
        );
        $addressMapper->map();
    }

}
