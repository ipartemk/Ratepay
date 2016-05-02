<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Ratepay\Business\Api\Converter;

use Spryker\Zed\Ratepay\Business\Api\Model\Parts\Payment;

class PaymentMapperTest extends AbstractMapperTest
{

    public function testMapper()
    {
        $payment = new Payment();

        $this->mapperFactory
            ->getPaymentMapper(
                $this->mockQuoteTransfer(),
                $this->mockPaymentElvTransfer(),
                $payment
            )
            ->map();

        $this->assertEquals(99, $payment->getAmount());
        $this->assertEquals('iso3', $payment->getCurrency());
        $this->assertEquals('invoice', $payment->getMethod());

    }

}
