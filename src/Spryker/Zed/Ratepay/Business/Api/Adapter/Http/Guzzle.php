<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Api\Adapter\Http;

use Guzzle\Http\Client as GuzzleClient;
use Guzzle\Http\Exception\RequestException;
use Spryker\Zed\Ratepay\Business\Api\Constants;
use Spryker\Zed\Ratepay\Business\Exception\ApiHttpRequestException;

class Guzzle extends AbstractHttpAdapter
{

    const DEFAULT_TIMEOUT = 45;

    /**
     * @var \Guzzle\Http\Client
     */
    protected $client;

    /**
     * @param string $gatewayUrl
     */
    public function __construct($gatewayUrl)
    {
        parent::__construct($gatewayUrl);

        $this->client = new GuzzleClient([
            'timeout' => self::DEFAULT_TIMEOUT,
        ]);
    }

    /**
     * @param string $data
     *
     * @return \Guzzle\Http\Message\RequestInterface
     */
    protected function buildRequest($data)
    {
        return $this->client->post(
            $this->gatewayUrl,
            ['Content-Type' => Constants::REQUEST_HEADER_CONTENT_TYPE]
        )->setBody($data);
    }

    /**
     * @param \Guzzle\Http\Message\RequestInterface $request
     *
     * @throws \Spryker\Zed\Ratepay\Business\Exception\ApiHttpRequestException
     *
     * @return string
     */
    protected function send($request)
    {
        try {
            $response = $request->send();
        } catch (RequestException $requestException) {
            throw new ApiHttpRequestException($requestException->getMessage());
        }

        return $response->getbody(true);
    }

}
