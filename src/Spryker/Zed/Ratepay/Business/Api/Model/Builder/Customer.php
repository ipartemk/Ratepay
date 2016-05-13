<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\Ratepay\Business\Api\Model\Builder;

/**
 * Class Customer
 * @package Spryker\Zed\Ratepay\Business\Api\Model\Builder
 */
class Customer extends AbstractBuilder implements BuilderInterface
{

    const ROOT_TAG = 'customer';

    /**
     * @return array
     */
    protected function buildData()
    {
        $customerData = [
            'first-name' => $this->requestTransfer->getCustomer()->getFirstName(),
            'last-name' => $this->requestTransfer->getCustomer()->getLastName(),
            'company-name' => '',
            'gender' => $this->requestTransfer->getCustomer()->getGender(),
            'date-of-birth' => $this->requestTransfer->getCustomer()->getDob(),
            'ip-address' => $this->requestTransfer->getCustomer()->getIpAddress(),
            'contacts' => [
                'email' => $this->requestTransfer->getCustomer()->getEmail(),
                'phone' => [
                    'direct-dial' => $this->requestTransfer->getCustomer()->getPhone(),
                ]
            ],
            'addresses' => [
                $this->requestTransfer->getBillingAddress(),
                $this->requestTransfer->getShippingAddress(),
            ],
            'customer-allow-credit-inquiry' => $this->requestTransfer->getCustomer()->getAllowCreditInquiry(),
        ];

        $bankAccount = $this->requestTransfer->getBankAccount();
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
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Parts\Customer
     */
    public function getStorage()
    {
        return $this->requestTransfer->getCustomer();
    }

}
