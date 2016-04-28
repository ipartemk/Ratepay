<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\Ratepay\Business\Payment\ConfigurationCalculation;

use Functional\Spryker\Zed\Ratepay\Business\Api\Adapter\Http\ConfigurationInstallmentAdapterMock;
use Spryker\Zed\Ratepay\Business\Api\Model\Parts\Head;
use Spryker\Zed\Ratepay\Business\Api\Model\Payment\Configuration;
use Spryker\Zed\Ratepay\Business\Api\Model\Response\ConfigurationResponse;

class InstallmentConfigurationTest extends InstallmentAbstractTest
{

    /**
     * @return void
     */
    public function testPaymentWithSuccessResponse()
    {
        $adapterMock = $this->getPaymentSuccessResponseAdapterMock();
        $facade = $this->getFacadeMock($adapterMock);
        $this->responseTransfer = $facade->installmentConfiguration($this->quoteTransfer);

        $this->assertInstanceOf('\Generated\Shared\Transfer\RatepayInstallmentConfigurationResponseTransfer', $this->responseTransfer);

        $expectedResponse = $this->sendRequest($adapterMock, $adapterMock->getSuccessResponse());


        $expectedResponseTransfer = $this->converterFactory
            ->getInstallmentConfigurationResponseConverter($expectedResponse, $this->getConfigurationRequest())
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
        $this->responseTransfer = $facade->installmentConfiguration($this->quoteTransfer);

        $this->assertInstanceOf('\Generated\Shared\Transfer\RatepayInstallmentConfigurationResponseTransfer', $this->responseTransfer);

        $expectedResponse = $this->sendRequest($adapterMock, $adapterMock->getFailureResponse());

        $expectedResponseTransfer = $this->converterFactory
            ->getInstallmentConfigurationResponseConverter($expectedResponse, $this->getConfigurationRequest())
            ->convert();

        $this->assertEquals($expectedResponseTransfer, $this->responseTransfer);

    }

    /**
     * @return \Functional\Spryker\Zed\Ratepay\Business\Api\Adapter\Http\ConfigurationInstallmentAdapterMock
     */
    protected function getPaymentSuccessResponseAdapterMock()
    {
        return new ConfigurationInstallmentAdapterMock();
    }

    /**
     * @return \Functional\Spryker\Zed\Ratepay\Business\Api\Adapter\Http\ConfigurationInstallmentAdapterMock
     */
    protected function getPaymentFailureResponseAdapterMock()
    {
        return (new ConfigurationInstallmentAdapterMock())->expectFailure();
    }

    /**
     * @param \Functional\Spryker\Zed\Ratepay\Business\Api\Adapter\Http\AbstractAdapterMock $adapterMock
     * @param string $request
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Response\ConfigurationResponse
     */
    protected function sendRequest($adapterMock, $request)
    {
        return new ConfigurationResponse($adapterMock->sendRequest($request));
    }

    /**
     * @return Configuration
     */
    protected function getConfigurationRequest()
    {
        return new Configuration(new Head('MyTestsystem', 'INTEGRATION_TE_DACH', '4c0a11923fa3433fb168f9c7176429e9'));
    }

}
