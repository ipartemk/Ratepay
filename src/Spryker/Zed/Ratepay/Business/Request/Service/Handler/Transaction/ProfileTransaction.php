<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Request\Service\Handler\Transaction;

use Spryker\Zed\Ratepay\Business\Api\Constants as ApiConstants;
use Spryker\Zed\Ratepay\Business\Api\Model\Response\ProfileResponse;
use Spryker\Zed\Ratepay\Business\Request\Service\Method\Service;
use Spryker\Zed\Ratepay\Business\Request\TransactionHandlerAbstract;
use Spryker\Zed\Ratepay\Business\Request\TransactionHandlerInterface;

/**
 * Class ProfileTransaction
 * @package Spryker\Zed\Ratepay\Business\Request\Service\Handler\Transaction
 */
class ProfileTransaction extends TransactionHandlerAbstract implements TransactionHandlerInterface
{

    const TRANSACTION_TYPE = ApiConstants::REQUEST_MODEL_PROFILE;

    /**
     * @return \Generated\Shared\Transfer\RatepayResponseTransfer
     */
    public function request()
    {

        $request = $this
            ->getMethodMapper(Service::METHOD)
            ->profile();

        $response = $this->sendRequest((string)$request);
        $this->logInfo($request, $response, Service::METHOD);

        $profileResponseTransfer = $this->converterFactory
            ->getProfileResponseConverter($response)
            ->convert();

        return $profileResponseTransfer;
    }

    /**
     * @param string $request
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Response\BaseResponse
     */
    protected function sendRequest($request)
    {
        return new ProfileResponse($this->executionAdapter->sendRequest($request));
    }

    /**
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Base $request
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Response\ResponseInterface $response
     * @param string $method
     * @param int|null $entityId
     *
     * @return void
     */
    protected function logInfo($request, $response, $method, $entityId = null)
    {
        //todo: implement logging.
    }

}
