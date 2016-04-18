<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\Ratepay\Business\Payment\PreAuthorize;

use Functional\Spryker\Zed\Ratepay\Business\Api\Adapter\Http\PreAuthorizeAdapterMock;
use Functional\Spryker\Zed\Ratepay\Business\Payment\InvoiceAbstractTest;
use Spryker\Zed\Ratepay\Business\Api\Converter\Converter;

class InvoiceTest extends InvoiceAbstractTest
{

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->setUpSalesOrderTestData();
        $this->setUpPaymentTestData();

        $this->converter = new Converter();
        $this->orderTransfer->fromArray($this->orderEntity->toArray(), true);

    }

    /**
     * @return \Functional\Spryker\Zed\Ratepay\Business\Api\Adapter\Http\PreAuthorizeAdapterMock
     */
    protected function getPaymentSuccessResponseAdapterMock()
    {
        return new PreAuthorizeAdapterMock();
    }

    /**
     * @return \Functional\Spryker\Zed\Ratepay\Business\Api\Adapter\Http\PreAuthorizeAdapterMock
     */
    protected function getPaymentFailureResponseAdapterMock()
    {
        return (new PreAuthorizeAdapterMock())->expectFailure();
    }

    /**
     * @param \Spryker\Zed\Ratepay\Business\RatepayFacade $facade
     *
     * @return \Generated\Shared\Transfer\RatepayResponseTransfer
     */
    protected function runFacadeMethod($facade)
    {
        return $facade->preAuthorizePayment($this->orderTransfer);
    }

}
