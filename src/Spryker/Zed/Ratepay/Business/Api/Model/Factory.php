<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Api\Model;

class Factory implements FactoryInterface
{

    /**
     * @var callable[]
     */
    protected $builders;

    /**
     * @param string $modelType
     * @param callable $builder
     *
     * @return $this
     */
    public function registerBuilder($modelType, callable $builder)
    {
        $this->builders[$modelType] = $builder;
        return $this;
    }

    /**
     * @param string $modelType
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\RequestInterface
     */
    public function build($modelType)
    {
        $builder = $this->builders[$modelType];
        return $builder();
    }

}
