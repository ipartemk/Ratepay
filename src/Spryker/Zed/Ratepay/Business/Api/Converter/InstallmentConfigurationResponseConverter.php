<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Api\Converter;

use Generated\Shared\Transfer\RatepayInstallmentConfigurationResponseTransfer;
use Spryker\Zed\Ratepay\Business\Api\Model\Response\ConfigurationResponse;

class InstallmentConfigurationResponseConverter extends BaseConverter
{

    /**
     * @var \Spryker\Zed\Ratepay\Business\Api\Model\Response\ConfigurationResponse
     */
    protected $response;

    /**
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Response\ConfigurationResponse $response
     */
    public function __construct(
        ConfigurationResponse $response
    ) {

        $this->response = $response;
    }

    /**
     * @return \Generated\Shared\Transfer\RatepayInstallmentConfigurationResponseTransfer
     */
    public function convert()
    {
        $responseTransfer = new RatepayInstallmentConfigurationResponseTransfer();
        $responseTransfer
            ->setTransactionId($this->response->getTransactionId())
            ->setTransactionShortId($this->response->getTransactionShortId())
            ->setSuccessful($this->response->isSuccessful())
            ->setReasonCode($this->response->getReasonCode())
            ->setReasonText($this->response->getReasonText())
            ->setStatusCode($this->response->getStatusCode())
            ->setStatusText($this->response->getStatusText())
            ->setResultCode($this->response->getResultCode())
            ->setResultText($this->response->getResultText())

            ->setInterestrateMin($this->response->getInterestrateMin())
            ->setInterestrateDefault($this->response->getInterestrateDefault())
            ->setInterestrateMax($this->response->getInterestrateMax())
            ->setInterestRateMerchantTowardsBank($this->response->getInterestRateMerchantTowardsBank())
            ->setMonthNumberMin($this->response->getMonthNumberMin())
            ->setMonthNumberMax($this->response->getMonthNumberMax())
            ->setMonthLongrun($this->response->getMonthLongrun())
            ->setAmountMinLongrun($this->response->getAmountMinLongrun())
            ->setMonthAllowed($this->response->getMonthAllowed())
            ->setValidPaymentFirstdays($this->response->getValidPaymentFirstdays())
            ->setPaymentFirstday($this->response->getPaymentFirstday())
            ->setPaymentAmount($this->response->getPaymentAmount())
            ->setPaymentLastrate($this->response->getPaymentLastrate())
            ->setRateMinNormal($this->response->getRateMinNormal())
            ->setRateMinLongrun($this->response->getRateMinLongrun())
            ->setServiceCharge($this->response->getServiceCharge())
            ->setMinDifferenceDueday($this->response->getMinDifferenceDueday());

        return $responseTransfer;
    }

}
