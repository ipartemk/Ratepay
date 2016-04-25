<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Api\Mapper;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\Ratepay\Business\Api\Model\Parts\ShoppingBasketItem;

class BasketItemMapper extends BaseMapper
{

    /**
     * @var ItemTransfer
     */
    protected $itemTransfer;

    /**
     * @var ShoppingBasketItem
     */
    protected $basketItem;

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Parts\ShoppingBasketItem $basketItem
     */
    public function __construct(
        ItemTransfer $itemTransfer,
        ShoppingBasketItem $basketItem
    )
    {
        $this->itemTransfer = $itemTransfer;
        $this->basketItem = $basketItem;
    }

    /**
     * @return void
     */
    public function map()
    {
        $this->basketItem->setItemName($this->itemTransfer->requireName()->getName());
        $this->basketItem->setArticleNumber($this->itemTransfer->requireSku()->getSku());
        $this->basketItem->setUniqueArticleNumber($this->itemTransfer->requireGroupKey()->getGroupKey());
        $this->basketItem->setQuantity($this->itemTransfer->requireQuantity()->getQuantity());
        $this->basketItem->setTaxRate($this->itemTransfer->requireTaxRate()->getTaxRate());
        $this->basketItem->setDescription($this->itemTransfer->getDescription());
        $this->basketItem->setDescriptionAddition($this->itemTransfer->getDescriptionAddition());

        $itemPrice = $this->centsToDecimal($this->itemTransfer->requireUnitGrossPriceWithProductOptions()->getUnitGrossPriceWithProductOptions());
        $this->basketItem->setUnitPriceGross($itemPrice);

        //todo:: uncomment discount amount when discount per item will be implemented.
        //$itemDiscount = $this->centsToDecimal($this->itemTransfer->requireUnitTotalDiscountAmountWithProductOption()->getUnitTotalDiscountAmountWithProductOption());
        //$this->basketItem->setDiscount($itemDiscount);

        // @todo: ProductOptions didn't tested, because we have no implementation for it now.
        foreach ($this->itemTransfer->getProductOptions() as $productOption) {
            $this->basketItem->addProductOption($productOption->getLabelOptionValue());
        }
    }

}
