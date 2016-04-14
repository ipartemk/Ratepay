<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Ratepay\Business\Payment\MethodMapper;

use Codeception\TestCase\Test;

class MapperTest extends Test
{

    public function testException()
    {
        $paymentTransactionHandler = new Transaction(
            $this->createAdapter($this->getConfig()->getTransactionGatewayUrl()),
            $this->createConverter(),
            $this->createMonolog(),
            $this->getQueryContainer()
        );

        $paymentTransactionHandler->registerMethodMapper($this->createInvoice());
        $paymentTransactionHandler->registerMethodMapper($this->createElv());
        $paymentTransactionHandler->registerMethodMapper($this->createPrepayment());

    }

}
