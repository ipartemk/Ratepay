<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Ratepay\Business\Api\Converter;

use Generated\Shared\Transfer\AddressTransfer;
use Spryker\Zed\Ratepay\Business\Api\Model\Parts\Address;

class AddressMapperTest extends AbstractMapperTest
{

    public function testMapper()
    {
        $addressTransfer = new AddressTransfer();
        $addressTransfer->setCity("s1")
            ->setIso2Code("iso2")
            ->setAddress1("addr1")
            ->setAddress2("addr2")
            ->setZipCode("zip")
            ->setFirstName("fn")
            ->setLastName("ln")
        ;
        $address = new Address();
        $this->mapperFactory
            ->getAddressMapper(
                $addressTransfer,
                'BILLING',
                $address
            )
            ->map();

        $this->assertEquals("s1", $address->getCity());
        $this->assertEquals("iso2", $address->getCountryCode());
        $this->assertEquals("addr1", $address->getStreet());
        $this->assertEquals("addr2", $address->getStreetNumber());
        $this->assertEquals("zip", $address->getZipCode());
        $this->assertNull($address->getFirstName());
        $this->assertNull($address->getLastName());

        $this->mapperFactory
            ->getAddressMapper(
                $addressTransfer,
                'DELIVERY',
                $address
            )
            ->map();

        $this->assertEquals("fn", $address->getFirstName());
        $this->assertEquals("ln", $address->getLastName());
    }


    


}
