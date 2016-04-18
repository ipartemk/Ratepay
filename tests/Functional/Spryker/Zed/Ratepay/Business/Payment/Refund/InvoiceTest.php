<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\Ratepay\Business\Payment\Refund;

use Functional\Spryker\Zed\Ratepay\Business\Api\Adapter\Http\RefundAdapterMock;
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
     * @return \Functional\Spryker\Zed\Ratepay\Business\Api\Adapter\Http\RefundAdapterMock
     */
    protected function getPaymentSuccessResponseAdapterMock()
    {
        return new RefundAdapterMock();
    }

    /**
     * @return \Functional\Spryker\Zed\Ratepay\Business\Api\Adapter\Http\RefundAdapterMock
     */
    protected function getPaymentFailureResponseAdapterMock()
    {
        return (new RefundAdapterMock())->expectFailure();
    }

    /**
     * @param \Spryker\Zed\Ratepay\Business\RatepayFacade $facade
     *
     * @return \Generated\Shared\Transfer\RatepayResponseTransfer
     */
    protected function runFacadeMethod($facade)
    {
        return $facade->refundPayment($this->orderTransfer);
    }

}
