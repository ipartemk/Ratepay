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
     * @var string
     */
    protected $owner;

    /**
     * @var string
     */
    protected $iban;

    /**
     * @var string
     */
    protected $bicSwift;

    /**
     * @param string $owner
     * @param string $iban
     * @param string $bicSwift
     */
    public function __construct($owner, $iban, $bicSwift)
    {
        $this->owner = $owner;
        $this->iban = $iban;
        $this->bicSwift = $bicSwift;
    }

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

    /**
     * @return string
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * @param string $owner
     *
     * @return $this
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return string
     */
    public function getIban()
    {
        return $this->iban;
    }

    /**
     * @param string $iban
     *
     * @return $this
     */
    public function setIban($iban)
    {
        $this->iban = $iban;

        return $this;
    }

    /**
     * @return string
     */
    public function getBicSwift()
    {
        return $this->bicSwift;
    }

    /**
     * @param string $bicSwift
     *
     * @return $this
     */
    public function setBicSwift($bicSwift)
    {
        $this->bicSwift = $bicSwift;

        return $this;
    }

}
