<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\Ratepay\Business\Api\Model\Parts;

use Spryker\Zed\Ratepay\Business\Api\Model\AbstractRequest;

class ShoppingBasket extends AbstractRequest
{

    const ROOT_TAG = 'shopping-basket';

    /**
     * @var float
     */
    protected $amount;

    /**
     * @var string
     */
    protected $currency;

    /**
     * @var array
     */
    protected $items = [];

    /**
     * @var float
     */
    protected $shippingUnitPrice;

    /**
     * @var float
     */
    protected $shippingTaxRate;

    /**
     * @var string
     */
    protected $shippingTitle = '';

    /**
     * @var float
     */
    protected $discountUnitPrice;

    /**
     * @var float
     */
    protected $discountTaxRate;

    /**
     * @var string
     */
    protected $discountTitle = '';

    /**
     * @return array
     */
    protected function buildData()
    {
        $return = [
            '@amount' => $this->getAmount(),
            '@currency' => $this->getCurrency(),
            'items' => [],
            'shipping' => [
                '@unit-price-gross' => $this->getShippingUnitPrice(),
                '@tax-rate' => $this->getShippingTaxRate(),
                '#' => $this->getShippingTitle(),
            ],
            'discount' => [
                '@unit-price-gross' => $this->getDiscountUnitPrice(),
                '@tax-rate' => $this->getDiscountTaxRate(),
                '#' => $this->getDiscountTitle(),
            ]
        ];

        $items = $this->getItems();
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
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     *
     * @return $this
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     *
     * @return $this
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * @return array
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param array $items
     *
     * @return $this
     */
    public function setItems($items)
    {
        $this->items = $items;

        return $this;
    }

    /**
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Parts\ShoppingBasketItem $item
     *
     * @return $this
     */
    public function addItem($item)
    {
        $this->items[] = $item;

        return $this;
    }

    /**
     * @return float
     */
    public function getShippingUnitPrice()
    {
        return $this->shippingUnitPrice;
    }

    /**
     * @param float $shippingUnitPrice
     *
     * @return $this
     */
    public function setShippingUnitPrice($shippingUnitPrice)
    {
        $this->shippingUnitPrice = $shippingUnitPrice;

        return $this;
    }

    /**
     * @return float
     */
    public function getShippingTaxRate()
    {
        return $this->shippingTaxRate;
    }

    /**
     * @param float $shippingTaxRate
     *
     * @return $this
     */
    public function setShippingTaxRate($shippingTaxRate)
    {
        $this->shippingTaxRate = $shippingTaxRate;

        return $this;
    }

    /**
     * @return string
     */
    public function getShippingTitle()
    {
        return $this->shippingTitle;
    }

    /**
     * @param string $shippingTitle
     *
     * @return $this
     */
    public function setShippingTitle($shippingTitle)
    {
        $this->shippingTitle = $shippingTitle;

        return $this;
    }

    /**
     * @return float
     */
    public function getDiscountUnitPrice()
    {
        return $this->discountUnitPrice;
    }

    /**
     * @param float $discountUnitPrice
     *
     * @return $this
     */
    public function setDiscountUnitPrice($discountUnitPrice)
    {
        $this->discountUnitPrice = $discountUnitPrice;

        return $this;
    }

    /**
     * @return float
     */
    public function getDiscountTaxRate()
    {
        return $this->discountTaxRate;
    }

    /**
     * @param float $discountTaxRate
     *
     * @return $this
     */
    public function setDiscountTaxRate($discountTaxRate)
    {
        $this->discountTaxRate = $discountTaxRate;

        return $this;
    }

    /**
     * @return string
     */
    public function getDiscountTitle()
    {
        return $this->discountTitle;
    }

    /**
     * @param string $discountTitle
     *
     * @return $this
     */
    public function setDiscountTitle($discountTitle)
    {
        $this->discountTitle = $discountTitle;

        return $this;
    }

}
