<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Communication\Plugin\Oms\Condition;

use Generated\Shared\Transfer\OrderTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Spryker\Zed\Kernel\Communication\AbstractPlugin as BaseAbstractPlugin;
use Spryker\Zed\Oms\Communication\Plugin\Oms\Condition\ConditionInterface;

/**
 * @method \Spryker\Zed\Ratepay\Business\RatepayFacade getFacade()
 */
class IsPaymentConfirmedPlugin extends BaseAbstractPlugin implements ConditionInterface
{

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItem
     *
     * @return bool
     */
    public function check(SpySalesOrderItem $orderItem)
    {
        $orderEntity = $orderItem->getOrder();

        $orderTransfer = new OrderTransfer();
        $orderTransfer->fromArray($orderEntity->toArray(), $ignoreMissingProperties = true);

        $isSuccess = $this->callFacade($orderTransfer);

        return $isSuccess;
    }

}