<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\Ratepay\Business\Payment\PreCheck;

use Functional\Spryker\Zed\Ratepay\Business\Api\Adapter\Http\PreCheckElvAdapterMock;
use Functional\Spryker\Zed\Ratepay\Business\Payment\ElvAbstractTest;

use Spryker\Shared\Ratepay\RatepayConstants;
use Spryker\Zed\Ratepay\Business\Api\Converter\Converter;

class ElvTest extends ElvAbstractTest
{

    /**
     * @return void
     */
    public function setUp()
    {
        $this->quoteTransfer = $this->getQuoteTransfer();
        $this->converter = new Converter();
    }

    /**
     * @return \Functional\Spryker\Zed\Ratepay\Business\Api\Adapter\Http\PreCheckElvAdapterMock
     */
    protected function getPaymentSuccessResponseAdapterMock()
    {
        return new PreCheckElvAdapterMock();
    }

    /**
     * @return \Functional\Spryker\Zed\Ratepay\Business\Api\Adapter\Http\PreCheckElvAdapterMock
     */
    protected function getPaymentFailureResponseAdapterMock()
    {
        return (new PreCheckElvAdapterMock())->expectFailure();
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

        $this->assertEquals(RatepayConstants::METHOD_ELV, $this->responseTransfer->getPaymentMethod());
        $this->assertEquals($this->expectedResponseTransfer->getPaymentMethod(), $this->responseTransfer->getPaymentMethod());
    }

}
