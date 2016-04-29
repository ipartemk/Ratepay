<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Ratepay\Business\Api\Converter;

use Spryker\Zed\Ratepay\Business\Api\Model\Parts\ShoppingBasket;

class BasketMapperTest extends AbstractMapperTest
{

    public function testMapper()
    {
        $basket = new ShoppingBasket();

        $this->mapperFactory
            ->getBasketMapper(
                $this->mockQuoteTransfer(),
                $this->mockPaymentElvTransfer(),
                $basket
            )
            ->map();

        $this->assertEquals(99, $basket->getAmount());
        $this->assertEquals("iso3", $basket->getCurrency());
        $this->assertEquals(89, $basket->getShippingUnitPrice());
    }

}
