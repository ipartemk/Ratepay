<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Api\Mapper;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RatepayPaymentElvTransfer;
use Generated\Shared\Transfer\RatepayPaymentInstallmentTransfer;
use Spryker\Zed\Ratepay\Business\Api\Model\Parts\InstallmentDetail;
use Spryker\Zed\Ratepay\Business\Api\Model\Parts\Payment;

class InstallmentPaymentMapper extends BaseMapper
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
     * @param \Generated\Shared\Transfer\RatepayPaymentInstallmentTransfer $ratepayPaymentTransfer
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
        $this->payment
            ->setDebitPayType($this->ratepayPaymentTransfer->getDebitPayType())
            ->setInstallmentDetails(new InstallmentDetail())
            ->setAmount(
                $this->centsToDecimal(
                    $this->quoteTransfer
                        ->getPayment()
                        ->getRatepayInstallment()
                        ->getInstallmentGrandTotalAmount()
                )
            )
        ;
    }

}