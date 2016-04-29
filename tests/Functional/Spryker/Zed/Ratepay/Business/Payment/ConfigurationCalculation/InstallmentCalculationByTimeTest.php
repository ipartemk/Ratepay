<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\Ratepay\Business\Payment\ConfigurationCalculation;

use Functional\Spryker\Zed\Ratepay\Business\Api\Adapter\Http\CalculationByTimeInstallmentAdapterMock;
use Spryker\Zed\Ratepay\Business\Api\Model\Parts\Head;
use Spryker\Zed\Ratepay\Business\Api\Model\Parts\InstallmentCalculation;
use Spryker\Zed\Ratepay\Business\Api\Model\Payment\Calculation;
use Spryker\Zed\Ratepay\Business\Api\Model\Response\CalculationResponse;

class InstallmentCalculationByTimeTest extends InstallmentAbstractTest
{

    /**
     * @return void
     */
    public function testPaymentWithSuccessResponse()
    {
        $adapterMock = $this->getPaymentSuccessResponseAdapterMock();
        $facade = $this->getFacadeMock($adapterMock);
        $this->responseTransfer = $facade->installmentCalculation($this->quoteTransfer);

        $this->assertInstanceOf('\Generated\Shared\Transfer\RatepayInstallmentCalculationResponseTransfer', $this->responseTransfer);

        $expectedResponse = $this->sendRequest($adapterMock, $adapterMock->getSuccessResponse());

        $expectedResponseTransfer = $this->converterFactory
            ->getInstallmentCalculationResponseConverter($expectedResponse, $this->getCalculationRequest())
            ->convert();

        $this->assertEquals($expectedResponseTransfer, $this->responseTransfer);
    }

    /**
     * @return void
     */
    public function testPaymentWithFailureResponse()
    {
        $adapterMock = $this->getPaymentFailureResponseAdapterMock();
        $facade = $this->getFacadeMock($adapterMock);
        $this->responseTransfer = $facade->installmentCalculation($this->quoteTransfer);

        $this->assertInstanceOf('\Generated\Shared\Transfer\RatepayInstallmentCalculationResponseTransfer', $this->responseTransfer);

        $expectedResponse = $this->sendRequest($adapterMock, $adapterMock->getFailureResponse());

        $expectedResponseTransfer = $this->converterFactory
            ->getInstallmentCalculationResponseConverter($expectedResponse, $this->getCalculationRequest())
            ->convert();

        $this->assertEquals($expectedResponseTransfer, $this->responseTransfer);
    }

    /**
     * @return \Functional\Spryker\Zed\Ratepay\Business\Api\Adapter\Http\CalculationByTimeInstallmentAdapterMock
     */
    protected function getPaymentSuccessResponseAdapterMock()
    {
        return new CalculationByTimeInstallmentAdapterMock();
    }

    /**
     * @return \Functional\Spryker\Zed\Ratepay\Business\Api\Adapter\Http\CalculationByTimeInstallmentAdapterMock
     */
    protected function getPaymentFailureResponseAdapterMock()
    {
        return (new CalculationByTimeInstallmentAdapterMock())->expectFailure();
    }

    /**
     * @param \Functional\Spryker\Zed\Ratepay\Business\Api\Adapter\Http\AbstractAdapterMock $adapterMock
     * @param string $request
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Response\CalculationResponse
     */
    protected function sendRequest($adapterMock, $request)
    {
        return new CalculationResponse($adapterMock->sendRequest($request));
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Calculation
     */
    protected function getCalculationRequest()
    {
        return new Calculation(
            new Head('MyTestsystem', 'INTEGRATION_TE_DACH', '4c0a11923fa3433fb168f9c7176429e9'),
            new InstallmentCalculation()
        );
    }

}
