<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Payment\Handler\Transaction;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Ratepay\Business\Api\Constants as ApiConstants;

class PreAuthorizePaymentTransaction extends BaseTransaction implements OrderTransactionInterface
{

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\RatepayResponseTransfer
     */
    public function request(OrderTransfer $orderTransfer)
    {
        $paymentMethod = $this->getPaymentMethod($orderTransfer);
        $request = $this
            ->getMethodMapper($paymentMethod->getPaymentType())
            ->paymentConfirm($orderTransfer);

        $response = $this->sendRequest((string)$request);
        $this->logInfo(ApiConstants::REQUEST_MODEL_PAYMENT_CONFIRM, $request, $response);

        if ($response->isSuccessful()) {
            $paymentMethod->setResultCode($response->getResultCode())->save();
        }
        return $this->converterFactory
            ->getTransferObjectConverter($response)
            ->convert();
    }

}
