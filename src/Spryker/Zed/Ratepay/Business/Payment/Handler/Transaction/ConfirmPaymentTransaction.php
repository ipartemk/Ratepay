<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Payment\Handler\Transaction;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Ratepay\Business\Api\Constants as ApiConstants;

class ConfirmPaymentTransaction extends BaseTransaction implements OrderTransactionInterface
{

    const TRANSACTION_TYPE = ApiConstants::REQUEST_MODEL_PAYMENT_CONFIRM;

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer[] $orderItems
     *
     * @return \Spryker\Shared\Transfer\AbstractTransfer
     */
    public function request(OrderTransfer $orderTransfer, array $orderItems = [])
    {
        $paymentMethod = $this->getPaymentMethod($orderTransfer);
        $request = $this
            ->getMethodMapper($paymentMethod->getPaymentType())
            ->paymentConfirm($orderTransfer);

        $response = $this->sendRequest((string)$request);
        $this->logInfo($request, $response, $paymentMethod->getPaymentType(), $paymentMethod->getFkSalesOrder());

        if ($response->isSuccessful()) {
            $paymentMethod->setResultCode($response->getResultCode())->save();
        }
        return $this->converterFactory
            ->getTransferObjectConverter($response)
            ->convert();
    }

}
