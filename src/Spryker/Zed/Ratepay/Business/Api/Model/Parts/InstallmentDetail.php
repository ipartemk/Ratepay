<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Api\Model\Parts;

use Spryker\Zed\Ratepay\Business\Api\Model\AbstractRequest;

class InstallmentDetail extends AbstractRequest
{

    const ROOT_TAG = 'installment-details';

    /**
     * @var int
     */
    protected $monthNumber;

    /**
     * @var float
     */
    protected $amount;

    /**
     * @var float
     */
    protected $lastAmount;

    /**
     * @var float
     */
    protected $interestRate;

    /**
     * @return array
     */
    protected function buildData()
    {
        $return = [
            'installment-number' => $this->getMonthNumber(),
            'installment-amount' => $this->getAmount(),
            'last-installment-amount' => $this->getLastAmount(),
            'interest-rate' => $this->getInterestRate(),
        ];

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
     * @return int
     */
    public function getMonthNumber()
    {
        return $this->monthNumber;
    }

    /**
     * @param int $monthNumber
     *
     * @return $this
     */
    public function setMonthNumber($monthNumber)
    {
        $this->monthNumber = $monthNumber;

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
    public function getLastAmount()
    {
        return $this->lastAmount;
    }

    /**
     * @param float $lastAmount
     *
     * @return $this
     */
    public function setLastAmount($lastAmount)
    {
        $this->lastAmount = $lastAmount;

        return $this;
    }

    /**
     * @return float
     */
    public function getInterestRate()
    {
        return $this->interestRate;
    }

    /**
     * @param float $interestRate
     *
     * @return $this
     */
    public function setInterestRate($interestRate)
    {
        $this->interestRate = $interestRate;

        return $this;
    }

}
