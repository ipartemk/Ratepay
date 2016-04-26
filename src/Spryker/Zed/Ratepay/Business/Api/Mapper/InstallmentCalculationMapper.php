<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Api\Mapper;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RatepayPaymentElvTransfer;
use Generated\Shared\Transfer\RatepayPaymentInstallmentTransfer;
use Spryker\Zed\Ratepay\Business\Api\Model\Parts\InstallmentCalculation;

class InstallmentCalculationMapper extends BaseMapper
{

    /**
     * @var QuoteTransfer
     */
    protected $quoteTransfer;

    /**
     * @var RatepayPaymentElvTransfer|RatepayPaymentInstallmentTransfer
     */
    protected $ratepayPaymentTransfer;

    /**
     * @var InstallmentCalculation
     */
    protected $calculation;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\RatepayPaymentInstallmentTransfer $ratepayPaymentTransfer
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Parts\InstallmentCalculation $calculation
     */
    public function __construct(
        QuoteTransfer $quoteTransfer,
        $ratepayPaymentTransfer,
        InstallmentCalculation $calculation
    )
    {
        $this->quoteTransfer = $quoteTransfer;
        $this->ratepayPaymentTransfer = $ratepayPaymentTransfer;
        $this->calculation = $calculation;
    }

    /**
     * @return void
     */
    public function map()
    {
        $grandTotal = $this->centsToDecimal(
            $this->quoteTransfer->requireTotals()
                ->getTotals()
                ->requireGrandTotal()
                ->getGrandTotal()
        );
        $this->calculation
            ->setSubType($this->ratepayPaymentTransfer->getInstallmentCalculationType())
            ->setAmount($grandTotal)
            ->setCalculationRate($this->ratepayPaymentTransfer->getInstallmentInterestRate())
            ->setMonth($this->ratepayPaymentTransfer->getInstallmentMonth())
            ->setPaymentFirstday($this->ratepayPaymentTransfer->getInstallmentPaymentFirstDay())
            ->setCalculationStart($this->ratepayPaymentTransfer->getInstallmentCalculationStart());
    }

}
