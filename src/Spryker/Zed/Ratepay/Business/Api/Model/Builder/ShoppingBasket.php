<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\Ratepay\Business\Api\Model\Builder;

class ShoppingBasket extends AbstractBuilder implements BuilderInterface
{

    const ROOT_TAG = 'shopping-basket';

    const DEFAULT_DISCOUNT_NODE_VALUE = 'Discount';

    const DEFAULT_SHIPPING_NODE_VALUE = 'Shipping costs';

    const BASKET_DISCOUNT_COEFFICIENT = -1;

    /**
     * @return array
     */
    protected function buildData()
    {
        $return = [
            '@amount' => $this->requestTransfer->getShoppingBasket()->getAmount(),
            '@currency' => $this->requestTransfer->getShoppingBasket()->getCurrency(),
            'items' => [],
        ];

        if ($this->requestTransfer->getShoppingBasket()->getShippingUnitPrice()) {
            $return['shipping'] = [
                '@unit-price-gross' => $this->requestTransfer->getShoppingBasket()->getShippingUnitPrice(),
                '@tax-rate' => $this->requestTransfer->getShoppingBasket()->getShippingTaxRate(),
                '#' => $this->requestTransfer->getShoppingBasket()->getShippingTitle(),
            ];
        }

        if ($this->requestTransfer->getShoppingBasket()->getDiscountUnitPrice()) {
            $return['discount'] = [
                '@unit-price-gross' => $this->requestTransfer->getShoppingBasket()->getDiscountUnitPrice() * self::BASKET_DISCOUNT_COEFFICIENT,
                '@tax-rate' => $this->requestTransfer->getShoppingBasket()->getDiscountTaxRate(),
                '#' => $this->requestTransfer->getShoppingBasket()->getDiscountTitle(),
            ];
        }

        $items = $this->requestTransfer->getShoppingBasket()->getItems();
        foreach ($items as $item) {
            $return['items'][] = $item;
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
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Parts\ShoppingBasket
     */
    public function getStorage()
    {
        return $this->requestTransfer->getShoppingBasket();
    }

}
