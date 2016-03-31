<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\Ratepay\Business\Api\Model\Parts;

use Spryker\Shared\Ratepay\RatepayConstants;
use Spryker\Zed\Ratepay\Business\Api\Model\RequestAbstract;

class Head extends RequestAbstract
{

    const ROOT_TAG = 'head';

    /**
     * @var string
     */
    protected $systemId;

    /**
     * @var string
     */
    protected $transactionId;

    /**
     * @var string
     */
    protected $profileId;

    /**
     * @var string
     */
    protected $securityCode;

    /**
     * @var string
     */
    protected $operation;

    /**
     * @var string
     */
    protected $operationSubstring;

    /**
     * @param string $systemId
     * @param string $profileId
     * @param string $securityCode
     */
    public function __construct($systemId, $profileId, $securityCode)
    {
        $this->systemId = $systemId;
        $this->profileId = $profileId;
        $this->securityCode = $securityCode;
    }

    /**
     * @return array
     */
    protected function buildData()
    {
        $return = [
            'system-id' => $this->getSystemId(),
            'credential' => [
                'profile-id'   => $this->getProfileId(),
                'securitycode' => $this->getSecurityCode()
            ],
            'meta' => [
                'systems' => [
                    'system' => [
                        '@name'    => RatepayConstants::CLIENT_NAME,
                        '@version' => RatepayConstants::CLIENT_VERSION,
                    ]
                ]
            ],
            'operation' => $this->getOperation(),
        ];

        if ($this->getOperationSubstring() !== null) {
            $return['operation'] = [
                '@subtype' => $this->getOperationSubstring(),
                '#' => $this->getOperation(),
            ];
        }

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
     * @param string $systemId
     *
     * @return $this
     */
    public function setSystemId($systemId)
    {
        $this->systemId = $systemId;
        return $this;
    }

    /**
     * @return string
     */
    public function getSystemId()
    {
        return $this->systemId;
    }

    /**
     * @param string $transactionId
     *
     * @return $this
     */
    public function setTransactionId($transactionId)
    {
        $this->transactionId = $transactionId;
        return $this;
    }

    /**
     * @return string
     */
    public function getTransactionId()
    {
        return $this->transactionId;
    }

    public function setProfileId($profileId)
    {
        $this->profileId = $profileId;
        return $this;
    }

    public function getProfileId()
    {
        return $this->profileId;
    }

    public function setSecurityCode($securityCode)
    {
        $this->securityCode = $securityCode;
        return $this;
    }

    public function getSecurityCode()
    {
        return $this->securityCode;
    }

    public function setOperation($operation)
    {
        $this->operation = $operation;
        return $this;
    }

    public function getOperation()
    {
        return $this->operation;
    }

    public function setOperationSubstring($operationSubstring)
    {
        $this->operationSubstring = $operationSubstring;
        return $this;
    }

    public function getOperationSubstring()
    {
        return $this->operationSubstring;
    }

}
