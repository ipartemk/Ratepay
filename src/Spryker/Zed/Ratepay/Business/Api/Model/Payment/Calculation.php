<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Api\Model\Payment;

use Spryker\Zed\Ratepay\Business\Api\Constants;
use Spryker\Zed\Ratepay\Business\Api\Model\Parts\Head;
use Spryker\Zed\Ratepay\Business\Api\Model\Parts\InstallmentCalculation;

class Calculation extends Base
{

    const OPERATION = Constants::REQUEST_MODEL_CALCULATION_REQUEST;

    /**
     * @var \Spryker\Zed\Ratepay\Business\Api\Model\Parts\InstallmentCalculation
     */
    protected $installmentCalculation;

    /**
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Parts\Head $head
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Parts\InstallmentCalculation $installmentCalculation
     */
    public function __construct(Head $head, InstallmentCalculation $installmentCalculation)
    {
        parent::__construct($head);
        $this->installmentCalculation = $installmentCalculation;
    }

    /**
     * @return array
     */
    protected function buildData()
    {
        $result = parent::buildData();
        $result['content'] = [
            $this->getInstallmentCalculation()->getRootTag() => $this->getInstallmentCalculation()
        ];

        return $result;
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Parts\InstallmentCalculation
     */
    public function getInstallmentCalculation()
    {
        return $this->installmentCalculation;
    }

    /**
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Parts\InstallmentCalculation $installmentCalculation
     *
     * @return $this
     */
    public function setInstallmentCalculation($installmentCalculation)
    {
        $this->installmentCalculation = $installmentCalculation;

        return $this;
    }

}
