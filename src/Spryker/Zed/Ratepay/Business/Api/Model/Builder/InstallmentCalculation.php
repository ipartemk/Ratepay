<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Api\Model\Builder;

class InstallmentCalculation extends AbstractBuilder implements BuilderInterface
{

    const ROOT_TAG = 'installment-calculation';

    const SUBTYPE_RATE = 'calculation-by-rate';
    const SUBTYPE_TIME = 'calculation-by-time';

    /**
     * @return array
     */
    protected function buildData()
    {
        $return = [
            'amount' => $this->installmentCalculation->getAmount(),
        ];
        if ($this->installmentCalculation->getPaymentFirstday() !== null) {
            $return['payment-firstday'] = $this->installmentCalculation->getPaymentFirstday();
        }
        if ($this->installmentCalculation->getCalculationStart() !== null) {
            $return['calculation-start'] = $this->installmentCalculation->getCalculationStart();
        }

        if ($this->installmentCalculation->getSubType() == self::SUBTYPE_RATE) {
            $return['calculation-rate'] = [
                'rate' => $this->installmentCalculation->getCalculationRate()
            ];
        }
        if ($this->installmentCalculation->getSubType() == self::SUBTYPE_TIME) {
            $return['calculation-time'] = [
                'month' => $this->installmentCalculation->getMonth()
            ];
        }

        return $return;
    }

    /**
     * @return string
     */
    public function getRootTag()
    {
        return static::ROOT_TAG;
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Parts\InstallmentCalculation
     */
    public function getStorage()
    {
        return $this->installmentCalculation;
    }

}
