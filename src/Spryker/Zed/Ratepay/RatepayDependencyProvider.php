<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class RatepayDependencyProvider extends AbstractBundleDependencyProvider
{

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        /*
        $container[self::FACADE_SALES_AGGREGATOR] = function (Container $container) {
            return new PayolutionToSalesAggregatorBridge($container->getLocator()->salesAggregator()->facade());
        };*/

        return $container;
    }

}
