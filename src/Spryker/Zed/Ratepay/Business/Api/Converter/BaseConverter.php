<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Api\Converter;

use Spryker\Shared\Library\Currency\CurrencyManager;

abstract class BaseConverter implements ConverterInterface
{

    /**
     * @param float $amount
     *
     * @return int
     */
    protected function decimalToCents($amount)
    {
        return CurrencyManager::getInstance()->convertDecimalToCent($amount);
    }

}
