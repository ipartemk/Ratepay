<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\Ratepay\Business\Payment\PreCheck;

use Functional\Spryker\Zed\Ratepay\Business\Api\Adapter\Http\PreCheckInstallmentAdapterMock;
use Functional\Spryker\Zed\Ratepay\Business\Payment\InstallmentAbstractTest;
use Spryker\Shared\Ratepay\RatepayConstants;

class InstallmentTest extends InstallmentAbstractTest
{

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->quoteTransfer = $this->getQuoteTransfer();
    }

    /**
     * @return \Functional\Spryker\Zed\Ratepay\Business\Api\Adapter\Http\PreCheckInvoiceAdapterMock
     */
    protected function getPaymentSuccessResponseAdapterMock()
    {
        return new PreCheckInstallmentAdapterMock();
    }

    /**
     * @return \Functional\Spryker\Zed\Ratepay\Business\Api\Adapter\Http\PreCheckInvoiceAdapterMock
     */
    protected function getPaymentFailureResponseAdapterMock()
    {
        return (new PreCheckInstallmentAdapterMock())->expectFailure();
    }

    /**
     * @param \Spryker\Zed\Ratepay\Business\RatepayFacade $facade
     *
     * @return \Generated\Shared\Transfer\RatepayResponseTransfer
     */
    protected function runFacadeMethod($facade)
    {
        return $facade->preCheckPayment($this->quoteTransfer);
    }

    /**
     * @return void
     */
    public function testPaymentWithSuccessResponse()
    {
        parent::testPaymentWithSuccessResponse();

        $this->assertEquals(RatepayConstants::METHOD_INSTALLMENT, $this->responseTransfer->getPaymentMethod());
        $this->assertEquals($this->expectedResponseTransfer->getPaymentMethod(), $this->responseTransfer->getPaymentMethod());
    }

}
