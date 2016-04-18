<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\Ratepay\Business\Payment\Init;

use Functional\Spryker\Zed\Ratepay\Business\Api\Adapter\Http\InitAdapterMock;
use Functional\Spryker\Zed\Ratepay\Business\Payment\PrepaymentAbstractTest;
use Spryker\Zed\Ratepay\Business\Api\Converter\Converter;

class PrepaymentTest extends PrepaymentAbstractTest
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
     * @return \Functional\Spryker\Zed\Ratepay\Business\Api\Adapter\Http\InitAdapterMock
     */
    protected function getPaymentSuccessResponseAdapterMock()
    {
        return new InitAdapterMock();
    }

    /**
     * @return \Functional\Spryker\Zed\Ratepay\Business\Api\Adapter\Http\InitAdapterMock
     */
    protected function getPaymentFailureResponseAdapterMock()
    {
        return (new InitAdapterMock())->expectFailure();
    }

    /**
     * @param \Spryker\Zed\Ratepay\Business\RatepayFacade $facade
     *
     * @return \Generated\Shared\Transfer\RatepayResponseTransfer
     */
    protected function runFacadeMethod($facade)
    {
        return $facade->initPayment($this->quoteTransfer);
    }

}
