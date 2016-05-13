<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Api\Model\Builder;

class Payment extends AbstractBuilder implements BuilderInterface
{

    const ROOT_TAG = 'payment';

    /**
     * @return array
     */
    protected function buildData()
    {
        $return = [
            '@method' => $this->requestTransfer->getPayment()->getMethod(),
            '@currency' => $this->requestTransfer->getPayment()->getCurrency(),
            'amount' => $this->requestTransfer->getPayment()->getAmount(),
            'debit-pay-type' => $this->requestTransfer->getPayment()->getDebitPayType(),
        ];

        if ($this->requestTransfer->getPayment()->getInstallmentDetails()) {
            $return['installment-details'] = $this->requestTransfer->getPayment()->getInstallmentDetails();
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
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Parts\Payment
     */
    public function getStorage()
    {
        return $this->requestTransfer->getPayment();
    }

}
