<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Ratepay\Business\Api\Converter;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\Ratepay\Business\Api\Model\Parts\ShoppingBasketItem;

class BasketItemMapperTest extends AbstractMapperTest
{

    public function testMapper()
    {
        $basketItem = new ShoppingBasketItem();
        $itemTransfer = new ItemTransfer();
        $itemTransfer->setName("q1")
            ->setSku("q2")
            ->setGroupKey("q3")
            ->setQuantity("q4")
            ->setTaxRate("q5")
            ->setUnitGrossPriceWithProductOptions(1200)
            ->setUnitTotalDiscountAmountWithProductOption(1400)
            ->setProductOptions(new \ArrayObject())
        ;

        $this->mapperFactory
            ->getBasketItemMapper(
                $itemTransfer,
                $basketItem
            )
            ->map();

        $this->assertEquals("q1", $basketItem->getItemName());
        $this->assertEquals("q2", $basketItem->getArticleNumber());
        $this->assertEquals("q3", $basketItem->getUniqueArticleNumber());
        $this->assertEquals("q4", $basketItem->getQuantity());
        $this->assertEquals("q5", $basketItem->getTaxRate());
        $this->assertEquals(12, $basketItem->getUnitPriceGross());
        $this->assertEquals(14, $basketItem->getDiscount());
    }

}
