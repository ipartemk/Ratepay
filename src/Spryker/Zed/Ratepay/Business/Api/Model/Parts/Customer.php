<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\Ratepay\Business\Api\Model\Parts;

use Spryker\Zed\Ratepay\Business\Api\Model\AbstractRequest;
use Spryker\Zed\Ratepay\Business\Api\Model\Parts\Address;
use Spryker\Zed\Ratepay\Business\Api\Model\Parts\BankAccount;

class Customer extends AbstractRequest
{

    const ROOT_TAG = 'customer';

    const ALLOW_CREDIT_INQUIRY_YES = 'yes';

    const ALLOW_CREDIT_INQUIRY_NO = 'no';

    public $allowCreditInquiryValues = [
        self::ALLOW_CREDIT_INQUIRY_YES,
        self::ALLOW_CREDIT_INQUIRY_NO,
    ];

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
    protected $allowCreditInquiry = self::ALLOW_CREDIT_INQUIRY_NO;

    /**
     * @var \Spryker\Zed\Ratepay\Business\Api\Model\Parts\BankAccount
     */
    protected $bankAccount;

    /**
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Parts\Address $billingAddress
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Parts\Address $shippingAddress
     */
    public function __construct(Address $billingAddress, Address $shippingAddress)
    {
        $this->billingAddress = $billingAddress;
        $this->shippingAddress = $shippingAddress;
    }

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
                'phone' => [
                    'direct-dial' => $this->getPhone(),
                ]
            ],
            'addresses' => [
                $this->getBillingAddress(),
                $this->getShippingAddress(),
            ],
            'customer-allow-credit-inquiry' => $this->getAllowCreditInquiry(),
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

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     *
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
     *
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
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param string $gender
     *
     * @return $this
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
        return $this;
    }

    /**
     * @return string
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @param string $company
     *
     * @return $this
     */
    public function setCompany($company)
    {
        $this->company = $company;
        return $this;
    }

    /**
     * @return string
     */
    public function getDob()
    {
        return $this->dob;
    }

    /**
     * @param string $dob
     *
     * @return $this
     */
    public function setDob($dob)
    {
        $this->dob = $dob;
        return $this;
    }

    /**
     * @return string
     */
    public function getIpAddress()
    {
        return $this->ipAddress;
    }

    /**
     * @param string $ipAddress
     *
     * @return $this
     */
    public function setIpAddress($ipAddress)
    {
        $this->ipAddress = $ipAddress;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     *
     * @return $this
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Parts\Address
     */
    public function getShippingAddress()
    {
        return $this->shippingAddress;
    }

    /**
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Parts\Address $shippingAddress
     *
     * @return $this
     */
    public function setShippingAddress(Address $shippingAddress)
    {
        $this->shippingAddress = $shippingAddress;
        return $this;
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Parts\Address|string
     */
    public function getBillingAddress()
    {
        return $this->billingAddress;
    }

    /**
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Parts\Address $billingAddress
     *
     * @return $this
     */
    public function setBillingAddress(Address $billingAddress)
    {
        $this->billingAddress = $billingAddress;
        return $this;
    }

    /**
     * @return string
     */
    public function getAllowCreditInquiry()
    {
        return $this->allowCreditInquiry;
    }

    /**
     * @param string $allowCreditInquiry
     *
     * @return $this
     */
    public function setAllowCreditInquiry($allowCreditInquiry)
    {
        $this->allowCreditInquiry = ($allowCreditInquiry === false)
            ? self::ALLOW_CREDIT_INQUIRY_NO : self::ALLOW_CREDIT_INQUIRY_YES;

        return $this;
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Parts\BankAccount
     */
    public function getBankAccount()
    {
        return $this->bankAccount;
    }

    /**
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Parts\BankAccount $bankAccount
     *
     * @return $this
     */
    public function setBankAccount(BankAccount $bankAccount)
    {
        $this->bankAccount = $bankAccount;
        return $this;
    }

}
