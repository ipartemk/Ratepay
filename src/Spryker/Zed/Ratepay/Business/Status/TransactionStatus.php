<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Status;

use Generated\Shared\Transfer\OrderTransfer;
use Orm\Zed\Ratepay\Persistence\SpyPaymentRatepayQuery;
use Spryker\Zed\Ratepay\Business\Api\Constants as ApiConstants;

class TransactionStatus implements TransactionStatusInterface
{

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Orm\Zed\Ratepay\Persistence\SpyPaymentRatepay
     */
    protected function loadOrderPayment(OrderTransfer $orderTransfer)
    {
        $query = new SpyPaymentRatepayQuery();
        return $query->findByFkSalesOrder($orderTransfer->requireIdSalesOrder()->getIdSalesOrder())->getFirst();
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isPreAuthorizationApproved(OrderTransfer $orderTransfer)
    {
        $payment = $this->loadOrderPayment($orderTransfer);
        return in_array(
            $payment->getResultCode(),
            [
                ApiConstants::REQUEST_CODE_SUCCESS_MATRIX[ApiConstants::REQUEST_MODEL_PAYMENT_CONFIRM],
                ApiConstants::REQUEST_CODE_SUCCESS_MATRIX[ApiConstants::REQUEST_MODEL_DELIVER_CONFIRM],
            ]
        );
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isCaptureApproved(OrderTransfer $orderTransfer)
    {
        $payment = $this->loadOrderPayment($orderTransfer);
        return in_array(
            $payment->getResultCode(),
            [
                ApiConstants::REQUEST_CODE_SUCCESS_MATRIX[ApiConstants::REQUEST_MODEL_DELIVER_CONFIRM],
            ]
        );
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isCancellationConfirmed(OrderTransfer $orderTransfer)
    {
        $payment = $this->loadOrderPayment($orderTransfer);
        return in_array(
            $payment->getResultCode(),
            [
                ApiConstants::REQUEST_CODE_SUCCESS_MATRIX[ApiConstants::REQUEST_MODEL_PAYMENT_CHANGE],
            ]
        );
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isRefundApproved(OrderTransfer $orderTransfer)
    {
        $payment = $this->loadOrderPayment($orderTransfer);
        return in_array(
            $payment->getResultCode(),
            [
                ApiConstants::REQUEST_CODE_SUCCESS_MATRIX[ApiConstants::REQUEST_MODEL_PAYMENT_CHANGE],
            ]
        );
    }

}
