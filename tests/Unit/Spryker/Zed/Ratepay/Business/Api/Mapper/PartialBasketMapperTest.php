<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Ratepay\Business\Api\Converter;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\Ratepay\Business\Api\Model\Parts\ShoppingBasket;

class PartialBasketMapperTest extends AbstractMapperTest
{

    public function testMapper()
    {
        $basket = new ShoppingBasket();

        $this->mapperFactory
            ->getPartialBasketMapper(
                $this->mockQuoteTransfer(),
                $this->mockPaymentElvTransfer(),
                [
                    (new ItemTransfer())->setSumGrossPriceWithProductOptionAndDiscountAmounts(9900),
                    (new ItemTransfer())->setSumGrossPriceWithProductOptionAndDiscountAmounts(1500),
                    (new ItemTransfer())->setSumGrossPriceWithProductOptionAndDiscountAmounts(100),
                ],
                $basket
            )
            ->map();

        $this->assertEquals(115, $basket->getAmount());
        $this->assertEquals("iso3", $basket->getCurrency());
    }

}
