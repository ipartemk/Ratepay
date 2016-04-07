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
    protected $discount;

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
            '@discount' => $this->getDiscount()
        ];

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

}
