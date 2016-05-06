<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Ratepay\Business\Api\Converter;

use Spryker\Zed\Ratepay\Business\Api\Model\Parts\InstallmentDetail;

class InstallmentDetailMapperTest extends AbstractMapperTest
{

    public function testMapper()
    {
        $installmentDetail = new InstallmentDetail();

        $this->mapperFactory
            ->getInstallmentDetailMapper(
                $this->mockQuoteTransfer(),
                $this->mockRatepayPaymentInstallmentTransfer(),
                $installmentDetail
            )
            ->map();

        $this->assertEquals(3, $installmentDetail->getRatesNumber());
        $this->assertEquals(12, $installmentDetail->getAmount());
        $this->assertEquals(14.5, $installmentDetail->getLastAmount());
        $this->assertEquals(0.14, $installmentDetail->getInterestRate());
        $this->assertEquals(28, $installmentDetail->getPaymentFirstday());
    }

}
