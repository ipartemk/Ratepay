<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Ratepay\Business\Api\Converter;

use Spryker\Zed\Ratepay\Business\Api\Model\Parts\Address;
use Spryker\Zed\Ratepay\Business\Api\Model\Parts\Customer;

class CustomerMapperTest extends AbstractMapperTest
{

    public function testMapper()
    {
        $customer = $this->mockCustomer();

        $this->mapperFactory
            ->getCustomerMapper(
                $this->mockQuoteTransfer(),
                $this->mockPaymentElvTransfer(),
                $customer
            )
            ->map();

        $this->assertEquals('yes', $customer->getAllowCreditInquiry());
        $this->assertEquals('m', $customer->getGender());
        $this->assertEquals('1980-01-02', $customer->getDob());
        $this->assertEquals('127.1.2.3', $customer->getIpAddress());
        $this->assertEquals('fn', $customer->getFirstName());
        $this->assertEquals('ln', $customer->getLastName());
        $this->assertEquals('email@site.com', $customer->getEmail());
        $this->assertEquals('0491234567', $customer->getPhone());
    }

    /**
     * @return Customer
     */
    protected function mockCustomer()
    {
        $billingAddress = new Address();
        $shippingAddress = new Address();
        $customer = new Customer(
            $billingAddress,
            $shippingAddress
        );

        return $customer;
    }

}
