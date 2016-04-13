<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\Ratepay\Business\Api\Model\Parts;

use Spryker\Zed\Ratepay\Business\Api\Model\AbstractRequest;

class ShoppingBasketItem extends AbstractRequest
{

    const ROOT_TAG = 'item';

    const ITEM_DISCOUNT_COEFFICIENT = -1;

    /**
     * @var string
     */
    protected $itemName;

    /**
     * @var string
     */
    protected $articleNumber;

    /**
     * @var string
     */
    protected $uniqueArticleNumber;

    /**
     * @var int
     */
    protected $quantity;

    /**
     * @var float
     */
    protected $unitPriceGross;

    /**
     * @var float
     */
    protected $taxRate;

    /**
     * @var float
     */
    protected $discount = 0;

    /**
     * @var array
     */
    protected $productOptions = [];

    /**
     * @return array
     */
    protected function buildData()
    {
        $return = [
            '@article-number' => $this->getArticleNumber(),
            '@unique-article-number' => $this->getUniqueArticleNumber(),
            '@quantity' => $this->getQuantity(),
            '@unit-price-gross' => $this->getUnitPriceGross(),
            '@tax-rate' => $this->getTaxRate(),
            '@discount' => $this->getDiscount() * self::ITEM_DISCOUNT_COEFFICIENT,
            '#' => $this->getItemName()
        ];
        if (count($this->getProductOptions())) {
            $return['@description-addition'] = implode("; ", $this->getProductOptions());
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
     * @return string
     */
    public function getItemName()
    {
        return $this->itemName;
    }

    /**
     * @param string $itemName
     * @return $this
     */
    public function setItemName($itemName)
    {
        $this->itemName = $itemName;

        return $this;
    }

    /**
     * @return string
     */
    public function getArticleNumber()
    {
        return $this->articleNumber;
    }

    /**
     * @param string $articleNumber
     *
     * @return $this
     */
    public function setArticleNumber($articleNumber)
    {
        $this->articleNumber = $articleNumber;

        return $this;
    }

    /**
     * @return string
     */
    public function getUniqueArticleNumber()
    {
        return $this->uniqueArticleNumber;
    }

    /**
     * @param string $uniqueArticleNumber
     *
     * @return $this
     */
    public function setUniqueArticleNumber($uniqueArticleNumber)
    {
        $this->uniqueArticleNumber = $uniqueArticleNumber;

        return $this;
    }

    /**
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     *
     * @return $this
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * @return float
     */
    public function getUnitPriceGross()
    {
        return $this->unitPriceGross;
    }

    /**
     * @param float $unitPriceGross
     *
     * @return $this
     */
    public function setUnitPriceGross($unitPriceGross)
    {
        $this->unitPriceGross = $unitPriceGross;

        return $this;
    }

    /**
     * @return float
     */
    public function getTaxRate()
    {
        return $this->taxRate;
    }

    /**
     * @param float $taxRate
     *
     * @return $this
     */
    public function setTaxRate($taxRate)
    {
        $this->taxRate = $taxRate;

        return $this;
    }

    /**
     * @return float
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * @param float $discount
     *
     * @return $this
     */
    public function setDiscount($discount)
    {
        $this->discount = $discount;

        return $this;
    }

    /**
     * @return array
     */
    public function getProductOptions()
    {
        return $this->productOptions;
    }

    /**
     * @param string $productOption
     * @return $this
     */
    public function addProductOption($productOption)
    {
        $this->productOptions[] = $productOption;

        return $this;
    }

    /**
     * @param array $productOptions
     * @return $this
     */
    public function setProductOptions($productOptions)
    {
        $this->productOptions = $productOptions;

        return $this;
    }

}
