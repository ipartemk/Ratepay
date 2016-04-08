<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Order;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Ratepay\Persistence\SpyPaymentRatepay;
use Spryker\Zed\Ratepay\Business\Order\MethodMapper\PaymentMethodMapperInterface;

class Saver implements SaverInterface
{

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     * @param \Spryker\Zed\Ratepay\Business\Order\MethodMapper\PaymentMethodMapperInterface $paymentMapper
     *
     * @return void
     */
    public function saveOrderPayment(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer, PaymentMethodMapperInterface $paymentMapper)
    {
        $paymentEntity = new SpyPaymentRatepay();
        $idSalesOrder = $checkoutResponseTransfer->getSaveOrder()->getIdSalesOrder();
        $paymentEntity
            ->setFkSalesOrder($idSalesOrder);

        $paymentMapper->mapMethodDataToPayment($quoteTransfer, $paymentEntity);
        $paymentEntity->save();
    }
}