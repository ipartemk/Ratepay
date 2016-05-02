<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Api\Model\Deliver;

use Spryker\Zed\Ratepay\Business\Api\Constants;
use Spryker\Zed\Ratepay\Business\Api\Model\Parts\Head;
use Spryker\Zed\Ratepay\Business\Api\Model\Parts\ShoppingBasket;
use Spryker\Zed\Ratepay\Business\Api\Model\Payment\Base;

class Confirm extends Base
{

    /**
     * Deliver confirmation operation.
     */
    const OPERATION = Constants::REQUEST_MODEL_DELIVER_CONFIRM;

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
        parent::__construct($head);
        $this->basket = $shoppingBasket;
    }

    /**
     * @return array
     */
    protected function buildData()
    {
        $this->getHead()->setOperation(static::OPERATION);
        $paymentRequestData = parent::buildData();
        $paymentRequestData['content'] = [
            $this->getShoppingBasket()->getRootTag() => $this->getShoppingBasket(),
        ];

        return $paymentRequestData;
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
