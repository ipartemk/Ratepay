<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\Ratepay\Business\Payment;

use Generated\Shared\Transfer\RatepayRequestTransfer;
use Spryker\Zed\Ratepay\Business\Api\Adapter\AdapterInterface;
use Spryker\Zed\Ratepay\Persistence\RatepayQueryContainer;
use Spryker\Zed\Ratepay\RatepayConfig;

class RatepayFacadeMockBuilder
{

    /**
     * @param \Spryker\Zed\Ratepay\Business\Api\Adapter\AdapterInterface $adapter
     * @param \PHPUnit_Framework_TestCase $testCase
     *
     * @return \Spryker\Zed\Ratepay\Business\RatepayFacade
     */
    public  function build(AdapterInterface $adapter, \PHPUnit_Framework_TestCase $testCase)
    {

        // Mock business factory to override return value of createExecutionAdapter to
        // place a mocked adapter that doesn't establish an actual connection.
        $businessFactoryMock = $this->getBusinessFactoryMock($adapter, $testCase);

        // Business factory always requires a valid query container. Since we're creating
        // functional/integration tests there's no need to mock the database layer.
        $queryContainer = new RatepayQueryContainer();
        $businessFactoryMock->setQueryContainer($queryContainer);

        // Mock the facade to override getFactory() and have it return out
        // previously created mock.
        $facade = $testCase->getMock(
            'Spryker\Zed\Ratepay\Business\RatepayFacade',
            ['getFactory']
        );
        $facade->expects($testCase->any())
            ->method('getFactory')
            ->will($testCase->returnValue($businessFactoryMock));

        return $facade;
    }

    /**
     * @param \Spryker\Zed\Ratepay\Business\Api\Adapter\AdapterInterface $adapter
     * @param \PHPUnit_Framework_TestCase $testCase
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Ratepay\Business\RatepayBusinessFactory
     */
    protected  function getBusinessFactoryMock(AdapterInterface $adapter, \PHPUnit_Framework_TestCase $testCase)
    {
        $businessFactoryMock = $testCase->getMock(
            'Spryker\Zed\Ratepay\Business\RatepayBusinessFactory',
            ['createAdapter', 'createRequestTransfer']
        );

        $created = new RatepayRequestTransfer();

        $businessFactoryMock->setConfig(new RatepayConfig());
        $businessFactoryMock
            ->expects($testCase->any())
            ->method('createAdapter')
            ->will($testCase->returnValue($adapter));
        $businessFactoryMock
            ->expects($testCase->any())
            ->method('createRequestTransfer')
//            ->will($testCase->returnValue(self::createRequestTransfer()));
            ->will($testCase->returnValue($created));

        return $businessFactoryMock;
    }

    //todo: fix temporary solution.
    static $requestTransfer = null;
    /**
     * @return \Generated\Shared\Transfer\RatepayRequestTransfer
     */
    protected function createRequestTransfer()
    {
        if (self::$requestTransfer === null) {
            self::$requestTransfer = new RatepayRequestTransfer();
        }

        return self::$requestTransfer;
    }

}
