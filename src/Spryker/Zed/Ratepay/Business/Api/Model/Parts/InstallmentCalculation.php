<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Api\Model\Parts;

use Spryker\Zed\Ratepay\Business\Api\Model\AbstractRequest;

class InstallmentCalculation extends AbstractRequest
{

    const ROOT_TAG = 'installment-calculation';

    const SUBTYPE_RATE = 'calculation-by-rate';
    const SUBTYPE_TIME = 'calculation-by-time';

    /**
     * @var array
     */
    protected $avalableSubtypes = [
        self::SUBTYPE_RATE,
        self::SUBTYPE_TIME,
    ];

    /**
     * @var float
     */
    protected $subType;

    /**
     * @var float
     */
    protected $amount;

    /**
     * @var float
     */
    protected $calculationRate;

    /**
     * @var int
     */
    protected $month;

    /**
     * @var int
     */
    protected $paymentFirstday;

    /**
     * @var string
     */
    protected $calculationStart;

    /**
     * @return array
     */
    protected function buildData()
    {
        $return = [
            'amount' => $this->getAmount(),
        ];
        if ($this->getPaymentFirstday() !== null) {
            $return['payment-firstday'] = $this->getPaymentFirstday();
        }
        if ($this->getCalculationStart() !== null) {
            $return['calculation-start'] = $this->getCalculationStart();
        }

        if ($this->getSubType() == self::SUBTYPE_RATE) {
            $return['calculation-rate'] = [
                'rate' => $this->getCalculationRate()
            ];
        }
        if ($this->getSubType() == self::SUBTYPE_TIME) {
            $return['calculation-time'] = [
                'month' => $this->getMonth()
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
     * @return float
     */
    public function getSubType()
    {
        return $this->subType;
    }

    /**
     * @param float $subType
     *
     * @return $this
     */
    public function setSubType($subType)
    {
        if (in_array($subType, $this->avalableSubtypes)) {
            $this->subType = $subType;
        }

        return $this;
    }

    /**
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     *
     * @return $this
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return float
     */
    public function getCalculationRate()
    {
        return $this->calculationRate;
    }

    /**
     * @param float $calculationRate
     *
     * @return $this
     */
    public function setCalculationRate($calculationRate)
    {
        $this->calculationRate = $calculationRate;

        return $this;
    }

    /**
     * @return int
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * @param int $month
     *
     * @return $this
     */
    public function setMonth($month)
    {
        $this->month = $month;

        return $this;
    }

    /**
     * @return int
     */
    public function getPaymentFirstday()
    {
        return $this->paymentFirstday;
    }

    /**
     * @param int $paymentFirstday
     *
     * @return $this
     */
    public function setPaymentFirstday($paymentFirstday)
    {
        $this->paymentFirstday = $paymentFirstday;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCalculationStart()
    {
        return $this->calculationStart;
    }

    /**
     * @param string $calculationStart
     *
     * @return $this
     */
    public function setCalculationStart($calculationStart)
    {
        $this->calculationStart = $calculationStart;

        return $this;
    }
    
    

}
