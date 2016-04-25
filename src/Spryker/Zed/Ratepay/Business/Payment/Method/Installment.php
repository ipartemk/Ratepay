<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Payment\Method;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RatepayPaymentInstallmentTransfer;
use Spryker\Shared\Ratepay\RatepayConstants;
use Spryker\Zed\Ratepay\Business\Api\Constants as ApiConstants;

/**
 * Ratepay Elv payment method.
 */
class Installment extends AbstractMethod
{

    /**
     * @const Payment method code.
     */
    const METHOD = RatepayConstants::METHOD_INSTALLMENT;

    /**
     * @return string
     */
    public function getMethodName()
    {
        return static::METHOD;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\RatepayPaymentInstallmentTransfer
     */
    public function getPaymentData(QuoteTransfer $quoteTransfer)
    {
        return $quoteTransfer
            ->requirePayment()
            ->getPayment()
            ->requireRatepayInstallment()
            ->getRatepayInstallment();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Request
     */
    public function configurationRequest(QuoteTransfer $quoteTransfer)
    {
        $paymentData = $this->getPaymentData($quoteTransfer);

        /**
         * @var \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Request $request
         */
        $request = $this->modelFactory->build(ApiConstants::REQUEST_MODEL_CONFIGURATION_REQUEST);
        $this->mapConfigurationData($quoteTransfer, $paymentData, $request);

        return $request;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Request
     */
    public function calculationRequest(QuoteTransfer $quoteTransfer)
    {
        $paymentData = $this->getPaymentData($quoteTransfer);

        /**
         * @var \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Calculation $request
         */
        $request = $this->modelFactory->build(ApiConstants::REQUEST_MODEL_CALCULATION_REQUEST);
        $this->mapCalculationData($quoteTransfer, $paymentData, $request);

        return $request;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\RatepayPaymentInstallmentTransfer $paymentData
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Request $request
     *
     * @return void
     */
    protected function mapConfigurationData($quoteTransfer, $paymentData, $request)
    {
        $request->getHead()
            ->setTransactionId($paymentData->getTransactionId())->setTransactionShortId($paymentData->getTransactionShortId())
            ->setCustomerId($quoteTransfer->getCustomer()->getIdCustomer());
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\RatepayPaymentInstallmentTransfer $paymentData
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Calculation $request
     *
     * @return void
     */
    protected function mapCalculationData($quoteTransfer, $paymentData, $request)
    {
        $request->getHead()
            ->setTransactionId($paymentData->getTransactionId())->setTransactionShortId($paymentData->getTransactionShortId())
            ->setCustomerId($quoteTransfer->getCustomer()->getIdCustomer())
            ->setOperationSubstring($paymentData->getInstallmentCalculationType());

        $this->converter->mapInstallmentCalculation($quoteTransfer, $paymentData, $request->getInstallmentCalculation());
    }

    /**
     *
     * @param \Orm\Zed\Ratepay\Persistence\SpyPaymentRatepay $payment
     *
     * @return \Generated\Shared\Transfer\RatepayPaymentInstallmentTransfer
     */
    protected function getPaymentTransferObject($payment)
    {
        return new RatepayPaymentInstallmentTransfer();
    }

}
