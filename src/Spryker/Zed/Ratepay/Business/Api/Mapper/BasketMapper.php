<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Api\Mapper;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RatepayPaymentElvTransfer;
use Generated\Shared\Transfer\RatepayPaymentInstallmentTransfer;
use Spryker\Zed\Ratepay\Business\Api\Model\Parts\ShoppingBasket;

class BasketMapper extends BaseMapper
{

    /**
     * @var QuoteTransfer|OrderTransfer
     */
    protected $quoteTransfer;

    /**
     * @var RatepayPaymentElvTransfer|RatepayPaymentInstallmentTransfer
     */
    protected $ratepayPaymentTransfer;

    /**
     * @var ShoppingBasket
     */
    protected $basket;

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Spryker\Shared\Transfer\TransferInterface $ratepayPaymentTransfer
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Parts\ShoppingBasket $basket
     */
    public function __construct(
        $quoteTransfer,
        $ratepayPaymentTransfer,
        ShoppingBasket $basket
    )
    {
        $this->quoteTransfer = $quoteTransfer;
        $this->ratepayPaymentTransfer = $ratepayPaymentTransfer;
        $this->basket = $basket;
    }

    /**
     * @return void
     */
    public function map()
    {
        $totalsTransfer = $this->quoteTransfer->requireTotals()->getTotals();

        $grandTotal = $this->centsToDecimal($totalsTransfer->requireGrandTotal()->getGrandTotal());
        $this->basket->setAmount($grandTotal);
        $this->basket->setCurrency($this->ratepayPaymentTransfer->requireCurrencyIso3()->getCurrencyIso3());

        $shippingUnitPrice = $this->centsToDecimal($totalsTransfer->requireExpenseTotal()->getExpenseTotal());
        $this->basket->setShippingUnitPrice($shippingUnitPrice);

        $discountUnitPrice = $this->centsToDecimal($totalsTransfer->requireDiscountTotal()->getDiscountTotal());
        $this->basket->setDiscountUnitPrice($discountUnitPrice);
    }

}
