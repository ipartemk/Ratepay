<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Api\Model\Payment;

use Spryker\Zed\Ratepay\Business\Api\Model\Parts\Head;
use Spryker\Zed\Ratepay\Business\Api\Model\Parts\ShoppingBasket;

class Refund extends Base
{

    /**
     * @const Method operation.
     */
    const OPERATION = 'PAYMENT_CHANGE';

    /**
     * @const Method operation subtype.
     */
    const OPERATION_SUBTYPE = 'return';

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
        $this->getHead()->setOperationSubstring(static::OPERATION_SUBTYPE);
        $data = parent::buildData();
        $data['content'] = [
            $this->getShoppingBasket()->getRootTag() => $this->basket,
        ];

        return $data;
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Parts\ShoppingBasket
     */
    public function getShoppingBasket()
    {
        return $this->basket;
    }

}
