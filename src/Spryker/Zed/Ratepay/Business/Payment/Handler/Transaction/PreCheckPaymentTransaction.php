<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Payment\Handler\Transaction;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Ratepay\Business\Api\Constants as ApiConstants;

class PreCheckPaymentTransaction extends BaseTransaction implements CheckoutTransactionInterface
{

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\RatepayResponseTransfer
     */
    public function request(QuoteTransfer $quoteTransfer)
    {
        //init payment call.
        $this->initPayment($quoteTransfer);

        //payment request call.
        $paymentMethod = $quoteTransfer
            ->requirePayment()
            ->getPayment()
            ->requirePaymentMethod()
            ->getPaymentMethod();

        $request = $this->getMethodMapper($paymentMethod)
            ->paymentRequest($quoteTransfer);
        $response = $this->sendRequest((string)$request);
        $this->logInfo(ApiConstants::REQUEST_MODEL_PAYMENT_REQUEST, $request, $response);

        $responseTransfer = $this->converter->responseToTransferObject($response);
        $this->fixResponseTransferTransactionId($responseTransfer, $responseTransfer->getTransactionId(), $responseTransfer->getTransactionShortId());

        return $responseTransfer;
    }

}
