<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Ratepay\Business\Payment\Handler\Transaction;

class CheckoutTransactionTest extends BaseTransactionTest
{

    public function testPreAuthorizationApproved()
    {
        $transactionHandler = $this->getTransactionHandlerObject('\Spryker\Zed\Ratepay\Business\Payment\Handler\Transaction\InitPaymentTransaction');

        $this->assertInstanceOf('\Spryker\Zed\Ratepay\Business\Payment\Handler\Transaction\CheckoutTransactionInterface', $transactionHandler);
    }

    public function testInitPayment()
    {
        $transactionHandler = $this->getTransactionHandlerObject('\Spryker\Zed\Ratepay\Business\Payment\Handler\Transaction\InitPaymentTransaction');
        $transactionHandler->registerMethodMapper($this->mockMethodInvoice());

        $ratepayResponseTransfer = $transactionHandler->request($this->mockQuoteTransfer());

        $this->assertInstanceOf('\Generated\Shared\Transfer\RatepayResponseTransfer', $ratepayResponseTransfer);

        $this->assertEquals(
            'Die Prüfung war erfolgreich. Vielen Dank, dass Sie die Zahlart Rechnung gewählt haben.',
            $ratepayResponseTransfer->getCustomerMessage()
        );
    }

    public function testPreCheckPayment()
    {
        $transactionHandler = $this->getTransactionHandlerObject('\Spryker\Zed\Ratepay\Business\Payment\Handler\Transaction\PreCheckPaymentTransaction');
        $transactionHandler->registerMethodMapper($this->mockMethodInvoice());

        $ratepayResponseTransfer = $transactionHandler->request($this->mockQuoteTransfer());

        $this->assertInstanceOf('\Generated\Shared\Transfer\RatepayResponseTransfer', $ratepayResponseTransfer);

        $this->assertEquals(
            'Die Prüfung war erfolgreich. Vielen Dank, dass Sie die Zahlart Rechnung gewählt haben.',
            $ratepayResponseTransfer->getCustomerMessage()
        );
    }

}
