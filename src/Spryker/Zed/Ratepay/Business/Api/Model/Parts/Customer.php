<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\Ratepay\Business\Api\Model\Parts;

use Spryker\Zed\Ratepay\Business\Api\Model\AbstractRequest;

class Customer extends AbstractRequest
{

    const ROOT_TAG = 'customer';

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
    protected $gender;

    /**
     * @var string
     */
    protected $company;

    /**
     * @var string
     */
    protected $dob;

    /**
     * @var string
     */
    protected $ipAddress;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var string
     */
    protected $phone;

    /**
     * @var string
     */
    protected $shippingAddress;

    /**
     * @var string
     */
    protected $billingAddress;

    /**
     * @var string
     */
    protected $allowCreditInquiry;

    /**
     * @var \Spryker\Zed\Ratepay\Business\Api\Model\Parts\BankAccount
     */
    protected $bankAccount;

    /**
     * @return array
     */
    protected function buildData()
    {
        $customerData = [
            'first-name' => $this->getFirstName(),
            'last-name' => $this->getLastName(),
            'company-name' => '',
            'gender' => $this->getGender(),
            'date-of-birth' => $this->getDob(),
            'ip-address' => $this->getIpAddress(),
            'contacts' => [
                'email' => $this->getEmail(),
                'phone' => $this->getPhone(),
            ],
            'addresses' => [
                $this->getBillingAddress(),
                $this->getShippingAddress(),
            ],
            'customer-allow-credit-inquiry' => $this->getAllowCreditInquiry()
        ];

        $bankAccount = $this->getBankAccount();
        if ($bankAccount !== null) {
            $customerData[$bankAccount->getRootTag()] = $bankAccount;
        }

        return $customerData;
    }

    /**
     * @return string
     */
    public function getRootTag()
    {
        return static::ROOT_TAG;
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

    public function getGender()
    {
        return $this->gender;
    }

    public function setGender($gender)
    {
        $this->gender = $gender;
        return $this;
    }

    public function getCompany()
    {
        return $this->company;
    }

    public function setCompany($company)
    {
        $this->company = $company;
    }

    public function getDob()
    {
        return $this->dob;
    }

    public function setDob($dob)
    {
        $this->dob = $dob;
        return $this;
    }

    public function getIpAddress()
    {
        return $this->ipAddress;
    }

    public function setIpAddress($ipAddress)
    {
        $this->ipAddress = $ipAddress;
        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    public function getPhone()
    {
        return $this->phone;
    }

    public function setPhone($phone)
    {
        $this->phone = $phone;
        return $this;
    }

    public function getShippingAddress()
    {
        return $this->shippingAddress;
    }

    public function setShippingAddress(Address $shippingAddress)
    {
        $this->shippingAddress = $shippingAddress;
        return $this;
    }

    public function getBillingAddress()
    {
        return $this->billingAddress;
    }

    public function setBillingAddress(Address $billingAddress)
    {
        $this->billingAddress = $billingAddress;
        return $this;
    }

    public function getAllowCreditInquiry()
    {
        return $this->allowCreditInquiry;
    }

    public function setAllowCreditInquiry($allowCreditInquiry)
    {
        $this->allowCreditInquiry = $allowCreditInquiry;
        return $this;
    }

    public function getBankAccount()
    {
        return $this->bankAccount;
    }

    public function setBankAccount(BankAccount $bankAccount)
    {
        $this->bankAccount = $bankAccount;
        return $this;
    }

}
