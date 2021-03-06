<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Ratepay\Business\Api\Converter;

class BasketMapperTest extends AbstractMapperTest
{

    public function testMapper()
    {
        $this->mapperFactory
            ->getBasketMapper(
                $this->mockQuoteTransfer(),
                $this->mockPaymentElvTransfer()
            )
            ->map();

        $this->assertEquals(99, $this->requestTransfer->getShoppingBasket()->getAmount());
        $this->assertEquals("iso3", $this->requestTransfer->getShoppingBasket()->getCurrency());
        $this->assertEquals(89, $this->requestTransfer->getShoppingBasket()->getShippingUnitPrice());
    }

}
