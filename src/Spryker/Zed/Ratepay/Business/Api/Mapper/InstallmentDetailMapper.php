<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Api\Mapper;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Ratepay\Business\Api\Model\Parts\InstallmentDetail;

class InstallmentDetailMapper extends BaseMapper
{

    /**
     * @var \Generated\Shared\Transfer\QuoteTransfer
     */
    protected $quoteTransfer;

    /**
     * @var \Generated\Shared\Transfer\RatepayPaymentElvTransfer|\Generated\Shared\Transfer\RatepayPaymentInstallmentTransfer
     */
    protected $ratepayPaymentTransfer;

    /**
     * @var \Spryker\Zed\Ratepay\Business\Api\Model\Parts\InstallmentDetail
     */
    protected $installmentDetail;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\RatepayPaymentInstallmentTransfer $ratepayPaymentTransfer
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Parts\InstallmentDetail $installmentDetail
     */
    public function __construct(
        QuoteTransfer $quoteTransfer,
        $ratepayPaymentTransfer,
        InstallmentDetail $installmentDetail
    ) {

        $this->quoteTransfer = $quoteTransfer;
        $this->ratepayPaymentTransfer = $ratepayPaymentTransfer;
        $this->installmentDetail = $installmentDetail;
    }

    /**
     * @return void
     */
    public function map()
    {
        $this->installmentDetail
            ->setMonthNumber($this->ratepayPaymentTransfer->getInstallmentMonth())
            ->setAmount($this->centsToDecimal($this->ratepayPaymentTransfer->getInstallmentRate()))
            ->setLastAmount($this->centsToDecimal($this->ratepayPaymentTransfer->getInstallmentLastRate()))
            ->setInterestRate($this->ratepayPaymentTransfer->getInstallmentInterestRate())
            ->setPaymentFirstday($this->ratepayPaymentTransfer->getInstallmentPaymentFirstDay());
    }

}
