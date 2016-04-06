<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\Ratepay\Business\Api\Model\Parts;

use Spryker\Zed\Ratepay\Business\Api\Model\AbstractRequest;

class Address extends AbstractRequest
{

    const ROOT_TAG = 'address';

    protected $addressType;

    protected $firstName;

    protected $lastName;

    /**
     * @return array
     */
    protected function buildData()
    {
        return [
            '@type' => $this->getAddressType(),
        ];
    }

    /**
     * @return string
     */
    public function getRootTag()
    {
        return static::ROOT_TAG;
    }

    public function getAddressType()
    {
        return $this->addressType;
    }

    public function setAddressType($addressType)
    {
        $this->addressType = $addressType;
        return $this;
    }

}
