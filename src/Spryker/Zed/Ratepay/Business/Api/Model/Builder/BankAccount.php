<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\Ratepay\Business\Api\Model\Builder;

class BankAccount extends AbstractBuilder implements BuilderInterface
{

    const ROOT_TAG = 'bank-account';

    /**
     * @return array
     */
    protected function buildData()
    {
        $return = [
            'owner' => $this->requestTransfer->getBankAccount()->getOwner(),
            'iban' => $this->requestTransfer->getBankAccount()->getIban(),
            'bic-swift' => $this->requestTransfer->getBankAccount()->getBicSwift(),
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

    /**
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Parts\BankAccount
     */
    public function getStorage()
    {
        return $this->requestTransfer->getBankAccount();
    }

}
