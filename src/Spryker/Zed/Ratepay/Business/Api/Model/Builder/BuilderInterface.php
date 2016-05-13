<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\Ratepay\Business\Api\Model\Builder;

interface BuilderInterface
{

    /**
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\AbstractRequest
     */
    public function getStorage();

}
