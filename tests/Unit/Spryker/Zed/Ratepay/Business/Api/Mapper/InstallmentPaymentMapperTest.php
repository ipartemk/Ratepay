<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Ratepay\Business\Api\Converter;

use Spryker\Zed\Ratepay\Business\Api\Model\Parts\Payment;

class InstallmentPaymentMapperTest extends AbstractMapperTest
{

    public function testMapper()
    {
        $payment = new Payment();
        $installment = $this->mockRatepayPaymentInstallmentTransfer();
        $quote = $this->mockQuoteTransfer();
        $quote->getPayment()
            ->setRatepayInstallment($installment);

        $this->mapperFactory
            ->getInstallmentPaymentMapper(
                $quote,
                $installment,
                $payment
            )
            ->map();

        $this->assertEquals('invoice', $payment->getDebitPayType());
        $this->assertEquals('125.7', $payment->getAmount());
    }

}
