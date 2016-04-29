<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Ratepay\Business\Api\Converter;

use Spryker\Zed\Ratepay\Business\Api\Model\Parts\BankAccount;

class BankAccountMapperTest extends AbstractMapperTest
{

    public function testMapper()
    {
        $bankAccount = new BankAccount();
        
        $this->mapperFactory
            ->getBankAccountMapper(
                $this->mockQuoteTransfer(),
                $this->mockPaymentElvTransfer(),
                $bankAccount
            )
            ->map();

        $this->assertEquals("iban", $bankAccount->getIban());
        $this->assertEquals("bic", $bankAccount->getBicSwift());
        $this->assertEquals("holder", $bankAccount->getOwner());
    }

}
