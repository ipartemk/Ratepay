<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Ratepay\Business\Api\Converter;

use Spryker\Zed\Ratepay\Business\Api\Model\Parts\InstallmentCalculation;

class InstallmentCalculationMapperTest extends AbstractMapperTest
{

    public function testMapper()
    {
        $calculation = new InstallmentCalculation(); 

        $this->mapperFactory
            ->getInstallmentCalculationMapper(
                $this->mockQuoteTransfer(),
                $this->mockRatepayPaymentInstallmentTransfer(),
                $calculation
            )
            ->map();

        $this->assertEquals('calculation-by-rate', $calculation->getSubType());
        $this->assertEquals(99, $calculation->getAmount());
        $this->assertEquals(14, $calculation->getCalculationRate());
        $this->assertEquals(3, $calculation->getMonth());
        $this->assertEquals(28, $calculation->getPaymentFirstday());
        $this->assertEquals('2016-05-15', $calculation->getCalculationStart());

    }

}
