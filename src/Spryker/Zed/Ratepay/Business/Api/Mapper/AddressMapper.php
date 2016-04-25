<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Api\Mapper;

use Generated\Shared\Transfer\AddressTransfer;
use Spryker\Zed\Ratepay\Business\Api\Constants as ApiConstants;
use Spryker\Zed\Ratepay\Business\Api\Model\Parts\Address;

class AddressMapper extends BaseMapper
{

    /**
     * @var AddressTransfer
     */
    protected $addressTransfer;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var Address
     */
    protected $address;


    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     * @param string $type
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Parts\Address $address
     */
    public function __construct(AddressTransfer $addressTransfer, $type, Address $address)
    {
        $this->addressTransfer = $addressTransfer;
        $this->type = $type;
        $this->address = $address;
    }

    /**
     * @return void
     */
    public function map()
    {
        $this->address->setAddressType($this->type)
            ->setCity($this->addressTransfer->requireCity()->getCity())
            ->setCountryCode($this->addressTransfer->requireIso2Code()->getIso2Code())
            ->setStreet($this->addressTransfer->requireAddress1()->getAddress1())
            ->setStreetAdditional($this->addressTransfer->getAddress3()) // This is optional.
            ->setStreetNumber($this->addressTransfer->requireAddress2()->getAddress2())
            ->setZipCode($this->addressTransfer->requireZipCode()->getZipCode());
        if ($this->type != ApiConstants::REQUEST_MODEL_ADDRESS_TYPE_BILLING) {
            $this->address
                ->setFirstName($this->addressTransfer->requireFirstName()->getFirstName())
                ->setLastName($this->addressTransfer->requireLastName()->getLastName());
        }
    }

}
