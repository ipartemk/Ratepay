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

    /**
     * @var string
     */
    protected $addressType;

    /**
     * @var string
     */
    protected $firstName;

    /**
     * @var string
     */
    protected $lastName;

    /**
     * @var string
     */
    protected $street;

    /**
     * @var string
     */
    protected $streetAdditional;

    /**
     * @var string
     */
    protected $streetNumber;

    /**
     * @var string
     */
    protected $zipCode;

    /**
     * @var string
     */
    protected $city;

    /**
     * @var string
     */
    protected $countryCode;

    /**
     * @return array
     */
    protected function buildData()
    {
        $result = [
            '@type' => $this->getAddressType(),
            'street' => $this->getStreet(),
            'street-additional' => $this->getStreetAdditional(),
            'street-number' => $this->getStreetNumber(),
            'zip-code' => $this->getZipCode(),
            'city' => $this->getCity(),
            'country-code' => $this->getCountryCode(),
        ];
        if ($this->getFirstName() !== null) {
            $result['first-name'] = $this->getFirstName();
        }
        if ($this->getLastName() !== null) {
            $result['last-name'] = $this->getLastName();
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
     * @return string
     */
    public function getAddressType()
    {
        return $this->addressType;
    }

    /**
     * @param string $addressType
     * @return $this
     */
    public function setAddressType($addressType)
    {
        $this->addressType = $addressType;
        return $this;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     * @return $this
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     * @return $this
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @param string $street
     * @return $this
     */
    public function setStreet($street)
    {
        $this->street = $street;
        return $this;
    }

    /**
     * @return string
     */
    public function getStreetNumber()
    {
        return $this->streetNumber;
    }

    /**
     * @param string $streetNumber
     * @return $this
     */
    public function setStreetNumber($streetNumber)
    {
        $this->streetNumber = $streetNumber;
        return $this;
    }

    /**
     * @return string
     */
    public function getStreetAdditional()
    {
        return $this->streetAdditional;
    }

    /**
     * @param string $streetAdditional
     * @return $this
     */
    public function setStreetAdditional($streetAdditional)
    {
        $this->streetAdditional = $streetAdditional;
        return $this;
    }

    /**
     * @return string
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }

    /**
     * @param string $zipCode
     * @return $this
     */
    public function setZipCode($zipCode)
    {
        $this->zipCode = $zipCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $city
     * @return $this
     */
    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @return string
     */
    public function getCountryCode()
    {
        return $this->countryCode;
    }

    /**
     * @param string $countryCode
     * @return $this
     */
    public function setCountryCode($countryCode)
    {
        $this->countryCode = $countryCode;
        return $this;
    }

}
