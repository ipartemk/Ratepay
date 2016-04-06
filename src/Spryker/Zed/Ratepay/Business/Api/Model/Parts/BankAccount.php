<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\Ratepay\Business\Api\Model\Parts;

use Spryker\Zed\Ratepay\Business\Api\Model\AbstractRequest;

class BankAccount extends AbstractRequest
{

    const ROOT_TAG = 'bank-account';

    /**
     * @return array
     */
    protected function buildData()
    {
        $return = [
            'owner' => $this->getOwner(),
            'iban' => $this->getIban(),
            'bic-swift' => $this->getBicSwift(),
        ];
        return $return;
    }

    /**
     * @return string
     */
    public function getRootTag()
    {
        return static::ROOT_TAG;
    }

}
