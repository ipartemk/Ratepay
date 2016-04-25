<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Api\Mapper;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RatepayPaymentElvTransfer;
use Generated\Shared\Transfer\RatepayPaymentInstallmentTransfer;
use Spryker\Zed\Ratepay\Business\Api\Model\Parts\Payment;

class PaymentMapper extends BaseMapper
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
     * @var Payment
     */
    protected $payment;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Spryker\Shared\Transfer\TransferInterface $ratepayPaymentTransfer
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Parts\Payment $payment
     */
    public function __construct(
        QuoteTransfer $quoteTransfer, 
        $ratepayPaymentTransfer, 
        Payment $payment
    )
    {
        $this->quoteTransfer = $quoteTransfer;
        $this->ratepayPaymentTransfer = $ratepayPaymentTransfer;
        $this->payment = $payment;
    }

    /**
     * @return void
     */
    public function map()
    {
        $totalsTransfer = $this->quoteTransfer->requireTotals()->getTotals();

        $this->payment->setCurrency($this->ratepayPaymentTransfer->requireCurrencyIso3()->getCurrencyIso3());
        $this->payment->setMethod($this->ratepayPaymentTransfer->requirePaymentType()->getPaymentType());

        $grandTotal = $this->centsToDecimal($totalsTransfer->requireGrandTotal()->getGrandTotal());
        $this->payment->setAmount($grandTotal);
    }

}
