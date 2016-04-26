<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Api\Converter;

use Generated\Shared\Transfer\RatepayResponseTransfer;
use Spryker\Zed\Ratepay\Business\Api\Model\Response\ResponseInterface;

class TransferObjectConverter extends BaseConverter
{

    /**
     * @var ResponseInterface
     */
    protected $response;

    /**
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Response\ResponseInterface $response
     */
    public function __construct(
        ResponseInterface $response
    )
    {
        $this->response = $response;
    }

    /**
     * @return \Generated\Shared\Transfer\RatepayResponseTransfer
     */
    public function convert()
    {
        $responseTransfer = new RatepayResponseTransfer();
        $responseTransfer
            ->setTransactionId($this->response->getTransactionId())
            ->setTransactionShortId($this->response->getTransactionShortId())
            ->setSuccessful($this->response->isSuccessful())
            ->setReasonCode($this->response->getReasonCode())
            ->setReasonText($this->response->getReasonText())
            ->setStatusCode($this->response->getStatusCode())
            ->setStatusText($this->response->getStatusText())
            ->setResultCode($this->response->getResultCode())
            ->setResultText($this->response->getResultText())
            ->setCustomerMessage($this->response->getCustomerMessage())
            ->setPaymentMethod($this->response->getPaymentMethod());

        return $responseTransfer;
    }

}
