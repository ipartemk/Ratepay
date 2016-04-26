<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Api\Converter;

use Generated\Shared\Transfer\RatepayInstallmentCalculationResponseTransfer;
use Spryker\Zed\Ratepay\Business\Api\Model\Payment\Calculation;
use Spryker\Zed\Ratepay\Business\Api\Model\Response\CalculationResponse;
use Spryker\Zed\Ratepay\Business\Api\Model\Response\ConfigurationResponse;

class InstallmentCalculationResponseConverter extends BaseConverter
{

    /**
     * @var ConfigurationResponse
     */
    protected $response;

    /**
     * @var Calculation
     */
    protected $request;

    /**
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Response\CalculationResponse $response
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Calculation $request
     */
    public function __construct(
        CalculationResponse $response,
        Calculation $request
    )
    {
        $this->response = $response;
        $this->request = $request;
    }

    /**
     * @return \Generated\Shared\Transfer\RatepayInstallmentCalculationResponseTransfer
     */
    public function convert()
    {
        $transferObjectConverter =  new TransferObjectConverter($this->response);

        $responseTransfer = new RatepayInstallmentCalculationResponseTransfer();
        $responseTransfer
            ->setBaseResponse($transferObjectConverter->convert())
            ->setSubtype($this->request->getInstallmentCalculation()->getSubType())

            ->setTotalAmount($this->decimalToCents($this->response->getTotalAmount()))
            ->setAmount($this->decimalToCents($this->response->getAmount()))
            ->setInterestAmount($this->decimalToCents($this->response->getInterestAmount()))
            ->setServiceCharge($this->decimalToCents($this->response->getServiceCharge()))
            ->setInterestRate($this->decimalToCents($this->response->getInterestRate()))
            ->setAnnualPercentageRate($this->response->getAnnualPercentageRate())
            ->setMonthlyDebitInterest($this->decimalToCents($this->response->getMonthlyDebitInterest()))
            ->setRate($this->decimalToCents($this->response->getRate()))
            ->setNumberOfRates($this->response->getNumberOfRates())
            ->setLastRate($this->decimalToCents($this->response->getLastRate()))
            ->setPaymentFirstDay($this->response->getPaymentFirstday())
        ;

        return $responseTransfer;
    }

}
