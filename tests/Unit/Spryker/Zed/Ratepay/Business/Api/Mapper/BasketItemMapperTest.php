<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Ratepay\Business\Api\Converter;

use Generated\Shared\Transfer\ItemTransfer;

class BasketItemMapperTest extends AbstractMapperTest
{

    public function testMapper()
    {
        $this->mapperFactory
            ->getBasketMapper(
                $this->mockQuoteTransfer(),
                $this->mockPaymentElvTransfer()
            )
            ->map();

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setName("q1")
            ->setSku("q2")
            ->setGroupKey("q3")
            ->setQuantity("q4")
            ->setTaxRate("q5")
            ->setUnitGrossPriceWithProductOptions(1200)
            ->setUnitTotalDiscountAmountWithProductOption(1400)
            ->setProductOptions(new \ArrayObject());

        $this->mapperFactory
            ->getBasketItemMapper(
                $itemTransfer
            )
            ->map();

        $this->assertEquals("q1", $this->requestTransfer->getShoppingBasket()->getItems()[0]->getItemName());
        $this->assertEquals("q2", $this->requestTransfer->getShoppingBasket()->getItems()[0]->getArticleNumber());
        $this->assertEquals("q3", $this->requestTransfer->getShoppingBasket()->getItems()[0]->getUniqueArticleNumber());
        $this->assertEquals("q4", $this->requestTransfer->getShoppingBasket()->getItems()[0]->getQuantity());
        $this->assertEquals("q5", $this->requestTransfer->getShoppingBasket()->getItems()[0]->getTaxRate());
        $this->assertEquals(12, $this->requestTransfer->getShoppingBasket()->getItems()[0]->getUnitPriceGross());
        $this->assertEquals(14, $this->requestTransfer->getShoppingBasket()->getItems()[0]->getDiscount());
    }

}
