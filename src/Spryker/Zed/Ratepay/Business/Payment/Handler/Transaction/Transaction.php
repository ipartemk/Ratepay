<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Payment\Handler\Transaction;

use Generated\Shared\Transfer\QuoteTransfer;


use Spryker\Zed\Ratepay\Business\Api\Adapter\AdapterInterface;
use Spryker\Zed\Ratepay\Business\Api\Converter\ConverterInterface;

class Transaction implements TransactionInterface
{

    /**
     * @var \Spryker\Zed\Payolution\Persistence\PayolutionQueryContainerInterface
     */
    private $queryContainer;

    /**
     * @param \Spryker\Zed\Ratepay\Business\Api\Adapter\AdapterInterface $executionAdapter
     * @param \Spryker\Zed\Ratepay\Business\Api\Converter\ConverterInterface $converter
     * @param \Spryker\Zed\Ratepay\Persistence\RatepayQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Ratepay\RatepayConfig $config
     */
    public function __construct(
        AdapterInterface $executionAdapter,
        ConverterInterface $converter,
        PayolutionQueryContainerInterface $queryContainer,
        PayolutionConfig $config
    ) {
        parent::__construct(
            $executionAdapter,
            $converter,
            $config
        );

        $this->queryContainer = $queryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\PayolutionTransactionResponseTransfer
     */
    public function preCheckPayment(QuoteTransfer $quoteTransfer)
    {
        $paymentTransfer = $quoteTransfer->getPayment()->getPayolution();
        $requestData = $this
            ->getMethodMapper($paymentTransfer->getAccountBrand())
            ->buildPreCheckRequest($quoteTransfer);

        return $this->sendRequest($requestData);
    }

}
