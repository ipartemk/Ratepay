<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Api\Model\Builder;

use Spryker\Zed\Ratepay\Business\Api\Model\AbstractRequest;
use Spryker\Zed\Ratepay\Business\Api\Model\Parts\InstallmentDetail as InstallmentDetailPart;

class InstallmentDetail extends AbstractRequest
{

    const ROOT_TAG = 'installment-details';

    protected $installmentDetail;

    /**
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Parts\InstallmentDetail $installmentDetail
     */
    public function __construct(InstallmentDetailPart $installmentDetail)
    {
        $this->installmentDetail = $installmentDetail;
    }

    /**
     * @return array
     */
    protected function buildData()
    {
        $return = [
            'installment-number' => $this->installmentDetail->getRatesNumber(),
            'installment-amount' => $this->installmentDetail->getAmount(),
            'last-installment-amount' => $this->installmentDetail->getLastAmount(),
            'interest-rate' => $this->installmentDetail->getInterestRate(),
            'payment-firstday' => $this->installmentDetail->getPaymentFirstday(),
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
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Parts\InstallmentDetail
     */
    public function getStorage()
    {
        return $this->installmentDetail;
    }

}
