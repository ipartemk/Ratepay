<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\Ratepay\Business\Api\Model\Parts;

use Spryker\Zed\Ratepay\Business\Api\Model\AbstractRequest;

class Address extends AbstractRequest
{

    const ROOT_TAG = 'address';

    protected $addressType;

    protected $firstName;

    protected $lastName;

    protected $street;

    protected $streetAdditional;

    protected $streetNumber;

    protected $zipCode;

    protected $city;

    protected $countryCode;

    /**
     * @return array
     */
    protected function buildData()
    {
        return [
            '@type' => $this->getAddressType(),
            'first-name' => $this->getFirstName(),
            'last-name' => $this->getLastName(),
            'street' => $this->getStreet(),
            'street-additional' => $this->getStreetAdditional(),
            'street-number' => $this->getStreetNumber(),
            'zip-code' => $this->getZipCode(),
            'city' => $this->getCity(),
            'country-code' => $this->getCountryCode(),
        ];
    }

    /**
     * @return string
     */
    public function getRootTag()
    {
        return static::ROOT_TAG;
    }

    public function getAddressType()
    {
        return $this->addressType;
    }

    public function setAddressType($addressType)
    {
        $this->addressType = $addressType;
        return $this;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getStreet()
    {
        return $this->street;
    }

    public function setStreet($street)
    {
        $this->street = $street;
        return $this;
    }

    public function getStreetNumber()
    {
        return $this->streetNumber;
    }

    public function setStreetNumber($streetNumber)
    {
        $this->streetNumber = $streetNumber;
        return $this;
    }

    public function getStreetAdditional()
    {
        return $this->streetAdditional;
    }

    public function setStreetAdditional($streetAdditional)
    {
        $this->streetAdditional = $streetAdditional;
        return $this;
    }

    public function getZipCode()
    {
        return $this->zipCode;
    }

    public function setZipCode($zipCode)
    {
        $this->zipCode = $zipCode;
        return $this;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }

    public function getCountryCode()
    {
        return $this->countryCode;
    }

    public function setCountryCode($countryCode)
    {
        $this->countryCode = $countryCode;
        return $this;
    }

}
