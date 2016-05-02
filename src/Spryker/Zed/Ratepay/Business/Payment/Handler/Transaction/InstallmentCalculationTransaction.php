<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Payment\Handler\Transaction;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Ratepay\Business\Api\Constants as ApiConstants;
use Spryker\Zed\Ratepay\Business\Api\Model\Response\CalculationResponse;

class InstallmentCalculationTransaction extends BaseTransaction implements QuoteTransactionInterface
{

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\RatepayInstallmentCalculationResponseTransfer
     */
    public function request(QuoteTransfer $quoteTransfer)
    {
        $paymentMethod = $quoteTransfer
            ->requirePayment()
            ->getPayment()
            ->requirePaymentMethod()
            ->getPaymentMethod();

        $paymentMethod = $this->getMethodMapper($paymentMethod);
        /** @var \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Calculation $request */
        $request = $paymentMethod
            ->calculationRequest($quoteTransfer);
        $response = $this->sendRequest((string)$request);
        $this->logInfo(ApiConstants::REQUEST_MODEL_CALCULATION_REQUEST, $request, $response);
        $responseTransfer = $this->converterFactory
            ->getInstallmentCalculationResponseConverter($response, $request)
            ->convert();

        return $responseTransfer;
    }

    /**
     * @param string $xmlRequest
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Response\CalculationResponse
     */
    protected function sendRequest($xmlRequest)
    {
        return new CalculationResponse($this->executionAdapter->sendRequest($xmlRequest));
    }

}
