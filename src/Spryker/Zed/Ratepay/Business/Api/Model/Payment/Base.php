<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\Ratepay\Business\Api\Model\Payment;

use Spryker\Zed\Ratepay\Business\Api\Model\Parts\Head;
use Spryker\Zed\Ratepay\Business\Api\Model\RequestAbstract;

abstract class Base extends RequestAbstract
{

    const ROOT_TAG = 'request';

    /**
     * @var \Spryker\Zed\Ratepay\Business\Api\Model\Parts\Head
     */
    protected $head;

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
        ];
    }

    /**
     * @return string
     */
    public function getRootTag()
    {
        return static::ROOT_TAG;
    }

    /**
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Parts\Head $head
     *
     * @return $this
     */
    public function setHead(Head $head)
    {
        $this->head = $head;
        return $this;
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Parts\Head
     */
    public function getHead()
    {
        return $this->head;
    }

}
