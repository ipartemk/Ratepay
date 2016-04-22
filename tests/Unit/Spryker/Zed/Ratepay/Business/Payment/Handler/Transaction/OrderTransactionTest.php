<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Ratepay\Business\Payment\Handler\Transaction;

class OrderTransactionTest extends BaseTransactionTest
{

    public function testPreAuthorizePayment()
    {
        $transactionHandler = $this->getTransactionHandlerObject('\Spryker\Zed\Ratepay\Business\Payment\Handler\Transaction\PreAuthorizePaymentTransaction');
        $transactionHandler->registerMethodMapper($this->mockMethodInvoice());

        $responseTransfer = $transactionHandler->request($this->mockOrderTransfer());

        $this->assertInstanceOf('\Generated\Shared\Transfer\RatepayResponseTransfer', $responseTransfer);

        $this->assertEquals(
            'Die Prüfung war erfolgreich. Vielen Dank, dass Sie die Zahlart Rechnung gewählt haben.',
            $responseTransfer->getCustomerMessage()
        );
    }

}
