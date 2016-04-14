<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Payment\Model;

use Orm\Zed\Ratepay\Persistence\SpyPaymentRatepayLog;
use Psr\Log\LoggerInterface;

class PaymentLogger implements LoggerInterface
{

    /**
     * @param string $message
     * @param array $context
     * @return null
     */
    public function emergency($message, array $context = [])
    {
        $this->persist($context);
    }

    /**
     * @param string $message
     * @param array $context
     * @return null
     */
    public function alert($message, array $context = [])
    {
        $this->persist($context);
    }

    /**
     * @param string $message
     * @param array $context
     * @return null
     */
    public function critical($message, array $context = [])
    {
        $this->persist($context);
    }

    /**
     * @param string $message
     * @param array $context
     * @return null
     */
    public function error($message, array $context = [])
    {
        $this->persist($context);
    }

    /**
     * @param string $message
     * @param array $context
     * @return null
     */
    public function warning($message, array $context = [])
    {
        $this->persist($context);
    }

    /**
     * @param string $message
     * @param array $context
     * @return null
     */
    public function notice($message, array $context = [])
    {
        $this->persist($context);
    }

    /**
     * @param string $message
     * @param array $context
     * @return null
     */
    public function info($message, array $context = [])
    {
        $this->persist($context);
    }

    /**
     * @param string $message
     * @param array $context
     * @return null
     */
    public function debug($message, array $context = [])
    {
        $this->persist($context);
    }

    /**
     * @param mixed $level
     * @param string $message
     * @param array $context
     * @return null
     */
    public function log($level, $message, array $context = [])
    {
        $this->persist($context);
    }

    /**
     * @param array $context
     * @return null
     */
    protected function persist(array $context = [])
    {
        $paymentLogEntity = new SpyPaymentRatepayLog();

        $paymentLogEntity->setFkSalesOrder($context['order_id']);

        $paymentLogEntity->setPaymentMethod($context['payment_method']);
        $paymentLogEntity->setRequestType($context['request_type']);
        $paymentLogEntity->setRequestTransactionId($context['request_transaction_id']);
        $paymentLogEntity->setRequestTransactionShortId($context['request_transaction_short_id']);
        $paymentLogEntity->setRequestBody($context['request_body']);

        $paymentLogEntity->setResponseType($context['response_type']);
        $paymentLogEntity->setResponseResultCode($context['response_result_code']);
        $paymentLogEntity->setResponseResultText($context['response_result_text']);
        $paymentLogEntity->setResponseTransactionId($context['response_transaction_id']);
        $paymentLogEntity->setResponseTransactionShortId($context['response_transaction_short_id']);
        $paymentLogEntity->setResponseReasonCode($context['response_reason_code']);
        $paymentLogEntity->setResponseReasonText($context['response_reason_text']);
        $paymentLogEntity->setResponseStatusCode($context['response_status_code']);
        $paymentLogEntity->setResponseStatusText($context['response_status_text']);
        $paymentLogEntity->setResponseCustomerMessage($context['response_customer_message']);

        $paymentLogEntity->save();
    }

}