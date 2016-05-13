<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\Ratepay\Business\Api\Model\Builder;

class ShoppingBasketItem extends AbstractBuilder implements BuilderInterface
{

    const ROOT_TAG = 'item';

    const ITEM_DISCOUNT_COEFFICIENT = -1;

    protected $shoppingBasketItem;

    /**
     * @return array
     */
    protected function buildData()
    {
        $return = [
            '@article-number' => $this->requestTransfer->getArticleNumber(),
            '@unique-article-number' => $this->shoppingBasketItem->getUniqueArticleNumber(),
            '@quantity' => $this->shoppingBasketItem->getQuantity(),
            '@unit-price-gross' => $this->shoppingBasketItem->getUnitPriceGross(),
            '@tax-rate' => $this->shoppingBasketItem->getTaxRate(),
            '@discount' => $this->shoppingBasketItem->getDiscount() * self::ITEM_DISCOUNT_COEFFICIENT,
            '#' => $this->shoppingBasketItem->getItemName()
        ];
        if ($this->shoppingBasketItem->getDescription() !== null) {
            $return['@description'] = $this->shoppingBasketItem->getDescription();
        }
        if ($this->shoppingBasketItem->getDescriptionAddition() !== null) {
            $return['@description-addition'] = $this->shoppingBasketItem->getDescriptionAddition();
        }

        return $return;
    }

    /**
     * @return string
     */
    public function getRootTag()
    {
        return static::ROOT_TAG;
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Parts\ShoppingBasketItem
     */
    public function getStorage()
    {
        return $this->shoppingBasketItem;
    }

}
