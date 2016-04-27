<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Payment\Handler\Transaction;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Ratepay\Business\Api\Constants as ApiConstants;
use Spryker\Zed\Ratepay\Business\Api\Model\Response\ConfigurationResponse;

class InstallmentConfigurationTransaction extends BaseTransaction implements CheckoutTransactionInterface
{

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\RatepayInstallmentConfigurationResponseTransfer
     */
    public function request(QuoteTransfer $quoteTransfer)
    {
        //payment request call.
        $paymentMethod = $quoteTransfer
            ->requirePayment()
            ->getPayment()
            ->requirePaymentMethod()
            ->getPaymentMethod();

        $request = $this->getMethodMapper($paymentMethod)
            ->configurationRequest($quoteTransfer);
        $response = $this->sendRequest((string)$request);
        $this->logInfo(ApiConstants::REQUEST_MODEL_CONFIGURATION_REQUEST, $request, $response);

        $responseTransfer = $this->converterFactory
            ->getInstallmentConfigurationResponseConverter($response, $request)
            ->convert();

        return $responseTransfer;
    }

    /**
     * @param string $request
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Response\ConfigurationResponse
     */
    protected function sendRequest($request)
    {
        return new ConfigurationResponse($this->executionAdapter->sendRequest($request));
    }

}
