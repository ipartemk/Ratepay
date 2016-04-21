<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Api\Model\Parts;

use Spryker\Zed\Ratepay\Business\Api\Model\AbstractRequest;

class Payment extends AbstractRequest
{

    const ROOT_TAG = 'payment';

    /**
     * @var string
     */
    protected $method;

    /**
     * @var string
     */
    protected $currency;

    /**
     * @var float
     */
    protected $amount;

    /**
     * @var
     */
    protected $installmentDetails;

    /**
     * @var
     */
    protected $debitPayType;

    /**
     * @return array
     */
    protected function buildData()
    {
        $return = [
            '@method' => $this->getMethod(),
            '@currency' => $this->getCurrency(),
            'amount' => $this->getAmount(),
            'installment-details' => $this->getInstallmentDetails(),
            'debit-pay-type' => $this->getDebitPayType(),
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
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param string $method
     *
     * @return $this
     */
    public function setMethod($method)
    {
        $this->method = $method;

        return $this;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     *
     * @return $this
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;

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
     * @return mixed
     */
    public function getInstallmentDetails()
    {
        return $this->installmentDetails;
    }

    /**
     * @param mixed $installmentDetails
     *
     * @return $this
     */
    public function setInstallmentDetails($installmentDetails)
    {
        $this->installmentDetails = $installmentDetails;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDebitPayType()
    {
        return $this->debitPayType;
    }

    /**
     * @param mixed $debitPayType
     *
     * @return $this
     */
    public function setDebitPayType($debitPayType)
    {
        $this->debitPayType = $debitPayType;

        return $this;
    }

}
