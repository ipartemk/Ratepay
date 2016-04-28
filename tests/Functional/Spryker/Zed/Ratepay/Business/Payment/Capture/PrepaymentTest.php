<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\Ratepay\Business\Payment\Capture;

use Functional\Spryker\Zed\Ratepay\Business\Api\Adapter\Http\CaptureAdapterMock;
use Functional\Spryker\Zed\Ratepay\Business\Payment\PrepaymentAbstractTest;

class PrepaymentTest extends PrepaymentAbstractTest
{

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->setUpSalesOrderTestData();
        $this->setUpPaymentTestData();

        $this->orderTransfer->fromArray($this->orderEntity->toArray(), true);
    }

    /**
     * @return \Functional\Spryker\Zed\Ratepay\Business\Api\Adapter\Http\CaptureAdapterMock
     */
    protected function getPaymentSuccessResponseAdapterMock()
    {
        return new CaptureAdapterMock();
    }

    /**
     * @return \Functional\Spryker\Zed\Ratepay\Business\Api\Adapter\Http\CaptureAdapterMock
     */
    protected function getPaymentFailureResponseAdapterMock()
    {
        return (new CaptureAdapterMock())->expectFailure();
    }

    /**
     * @param \Spryker\Zed\Ratepay\Business\RatepayFacade $facade
     *
     * @return \Generated\Shared\Transfer\RatepayResponseTransfer
     */
    protected function runFacadeMethod($facade)
    {
        return $facade->capturePayment($this->orderTransfer, $this->orderTransfer->getItems()->getArrayCopy());
    }

}
