<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\Ratepay\Business\Api\Model\Parts;

use Spryker\Shared\Ratepay\RatepayConstants;
use Spryker\Zed\Ratepay\Business\Api\Model\AbstractRequest;

class Head extends AbstractRequest
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
    protected $transactionShortId;

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
     * @var string
     */
    protected $externalOrderId;

    /**
     * @var string
     */
    protected $customerId;

    /**
     * @var string
     */
    protected $deviceFingerprint;

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
            'transaction-id' => $this->getTransactionId(),
            'transaction-short-id' => $this->getTransactionShortId(),
            'credential' => [
                'profile-id'   => $this->getProfileId(),
                'securitycode' => $this->getSecurityCode()
            ],
            'customer-device' => [
                'device-token' => $this->getDeviceFingerprint()
            ],
            'external' => [
                'merchant-consumer-id' => $this->getCustomerId(),
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

        if ($this->getExternalOrderId() !== null) {
            $return['external'] = [
                'order-id' => $this->getExternalOrderId(),
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

    /**
     * @param string $transactionShortId
     *
     * @return $this
     */
    public function setTransactionShortId($transactionShortId)
    {
        $this->transactionShortId = $transactionShortId;
        return $this;
    }

    /**
     * @return string
     */
    public function getTransactionShortId()
    {
        return $this->transactionShortId;
    }

    /**
     * @param string $profileId
     * @return $this
     */
    public function setProfileId($profileId)
    {
        $this->profileId = $profileId;
        return $this;
    }

    /**
     * @return string
     */
    public function getProfileId()
    {
        return $this->profileId;
    }

    /**
     * @param string $securityCode
     * @return $this
     */
    public function setSecurityCode($securityCode)
    {
        $this->securityCode = $securityCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getSecurityCode()
    {
        return $this->securityCode;
    }

    /**
     * @param string $operation
     * @return $this
     */
    public function setOperation($operation)
    {
        $this->operation = $operation;
        return $this;
    }

    /**
     * @return string
     */
    public function getOperation()
    {
        return $this->operation;
    }

    /**
     * @param string $operationSubstring
     * @return $this
     */
    public function setOperationSubstring($operationSubstring)
    {
        $this->operationSubstring = $operationSubstring;
        return $this;
    }

    /**
     * @return string
     */
    public function getOperationSubstring()
    {
        return $this->operationSubstring;
    }

    /**
     * @return string
     */
    public function getExternalOrderId()
    {
        return $this->externalOrderId;
    }

    /**
     * @param string $externalOrderId
     *
     * @return $this
     */
    public function setExternalOrderId($externalOrderId)
    {
        $this->externalOrderId = $externalOrderId;

        return $this;
    }

    /**
     * @return string
     */
    public function getCustomerId()
    {
        return $this->customerId;
    }

    /**
     * @param string $customerId
     *
     * @return $this
     */
    public function setCustomerId($customerId)
    {
        $this->customerId = $customerId;

        return $this;
    }

    /**
     * @return string
     */
    public function getDeviceFingerprint()
    {
        return $this->deviceFingerprint;
    }

    /**
     * @param string $deviceFingerprint
     * @return $this
     */
    public function setDeviceFingerprint($deviceFingerprint)
    {
        $this->deviceFingerprint = $deviceFingerprint;

        return $this;
    }

}
