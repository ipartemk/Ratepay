<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Api\Mapper;

use Spryker\Zed\Ratepay\Business\Api\Model\Parts\ShoppingBasket;

class PartialBasketMapper extends BaseMapper
{

    /**
     * @var \Generated\Shared\Transfer\QuoteTransfer|\Generated\Shared\Transfer\OrderTransfer
     */
    protected $quoteTransfer;

    /**
     * @var \Generated\Shared\Transfer\RatepayPaymentElvTransfer|\Generated\Shared\Transfer\RatepayPaymentInstallmentTransfer
     */
    protected $ratepayPaymentTransfer;

    /**
     * @var \Spryker\Zed\Ratepay\Business\Api\Model\Parts\ShoppingBasket
     */
    protected $basket;

    /**
     * @var
     */
    protected $basketItems;

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Spryker\Shared\Transfer\TransferInterface $ratepayPaymentTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer[] $basketItems
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Parts\ShoppingBasket $basket
     */
    public function __construct(
        $quoteTransfer,
        $ratepayPaymentTransfer,
        array $basketItems,
        ShoppingBasket $basket
    ) {

        $this->quoteTransfer = $quoteTransfer;
        $this->ratepayPaymentTransfer = $ratepayPaymentTransfer;
        $this->basketItems = $basketItems;
        $this->basket = $basket;
    }

    /**
     * @return void
     */
    public function map()
    {
        $grandTotal = 0;
        foreach ($this->basketItems as $basketItem) {
            $grandTotal += $basketItem->getUnitGrossPriceWithProductOptions();
        }
        $this->basket->setAmount($this->centsToDecimal($grandTotal));
        $this->basket->setCurrency($this->ratepayPaymentTransfer->requireCurrencyIso3()->getCurrencyIso3());

//        $shippingUnitPrice = $this->centsToDecimal($totalsTransfer->requireExpenseTotal()->getExpenseTotal());
//        $this->basket->setShippingUnitPrice($shippingUnitPrice);

//        $discountUnitPrice = $this->centsToDecimal($totalsTransfer->requireDiscountTotal()->getDiscountTotal());
//        $this->basket->setDiscountUnitPrice($discountUnitPrice);
    }

}
