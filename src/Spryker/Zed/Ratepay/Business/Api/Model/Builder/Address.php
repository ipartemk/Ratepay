<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\Ratepay\Business\Api\Model\Builder;

class Address extends AbstractBuilder implements BuilderInterface
{

    const ROOT_TAG = 'address';

    /**
     * @return array
     */
    protected function buildData()
    {
        $result = [
            '@type' => $this->requestTransfer->getBillingAddress()->getAddressType(),
            'street' => $this->requestTransfer->getBillingAddress()->getStreet(),
            'street-additional' => $this->requestTransfer->getBillingAddress()->getStreetAdditional(),
            'street-number' => $this->requestTransfer->getBillingAddress()->getStreetNumber(),
            'zip-code' => $this->requestTransfer->getBillingAddress()->getZipCode(),
            'city' => $this->requestTransfer->getBillingAddress()->getCity(),
            'country-code' => $this->requestTransfer->getBillingAddress()->getCountryCode(),
        ];
        if ($this->requestTransfer->getBillingAddress()->getFirstName() !== null) {
            $result['first-name'] = $this->requestTransfer->getBillingAddress()->getFirstName();
        }
        if ($this->requestTransfer->getBillingAddress()->getLastName() !== null) {
            $result['last-name'] = $this->requestTransfer->getBillingAddress()->getLastName();
        }

        return $result;
    }

    /**
     * @return string
     */
    public function getRootTag()
    {
        return static::ROOT_TAG;
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Parts\Address
     */
    public function getStorage()
    {
        return $this->requestTransfer->getBillingAddress();
    }

}
