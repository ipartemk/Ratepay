<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Ratepay\Business\Api\Converter;

class InstallmentDetailMapperTest extends AbstractMapperTest
{

    public function testMapper()
    {
        $this->mapperFactory
            ->getInstallmentDetailMapper(
                $this->mockQuoteTransfer(),
                $this->mockRatepayPaymentInstallmentTransfer()
            )
            ->map();

        $this->assertEquals(3, $this->requestTransfer->getInstallmentDetails()->getRatesNumber());
        $this->assertEquals(12, $this->requestTransfer->getInstallmentDetails()->getAmount());
        $this->assertEquals(14.5, $this->requestTransfer->getInstallmentDetails()->getLastAmount());
        $this->assertEquals(0.14, $this->requestTransfer->getInstallmentDetails()->getInterestRate());
        $this->assertEquals(28, $this->requestTransfer->getInstallmentDetails()->getPaymentFirstday());
    }

}
