<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Api\Model\Deliver;

use Spryker\Zed\Ratepay\Business\Api\Model\Parts\Head;
use Spryker\Zed\Ratepay\Business\Api\Model\Parts\ShoppingBasket;
use Spryker\Zed\Ratepay\Business\Api\Model\Payment\Base;

class Confirm extends Base
{

    const OPERATION = 'CONFIRMATION_DELIVER';

    /**
     * @var \Spryker\Zed\Ratepay\Business\Api\Model\Parts\ShoppingBasket
     */
    protected $basket;

    /**
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Parts\Head $head
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Parts\ShoppingBasket $shoppingBasket
     */
    public function __construct(Head $head, ShoppingBasket $shoppingBasket)
    {
        $this->head = $head;
        $this->basket = $shoppingBasket;
    }

    /**
     * @return array
     */
    protected function buildData()
    {
        $this->getHead()->setOperation(static::OPERATION);
        return [
            '@version' => '1.0',
            '@xmlns' => "urn://www.ratepay.com/payment/1_0",
            $this->getHead()->getRootTag() => $this->getHead(),
            'content' => [
                $this->getShoppingBasket()->getRootTag() => $this->getShoppingBasket(),
            ],
        ];
    }

    /**
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Parts\ShoppingBasket $basket
     *
     * @return $this
     */
    public function setShoppingBasket(ShoppingBasket $basket)
    {
        $this->basket = $basket;
        return $this;
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Parts\ShoppingBasket
     */
    public function getShoppingBasket()
    {
        return $this->basket;
    }

}
