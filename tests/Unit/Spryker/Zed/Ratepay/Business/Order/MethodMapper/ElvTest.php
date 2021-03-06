<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Ratepay\Business\Order\MethodMapper;

use Generated\Shared\Transfer\RatepayPaymentElvTransfer;
use Spryker\Zed\Ratepay\Business\Order\MethodMapper\Elv;

class ElvTest extends BaseMethodMapperTest
{

    /**
     * @return void
     */
    public function setUp()
    {
        $this->paymentMethod = 'ELV';

        parent::setUp();
    }

    public function testMapMethodDataToPayment()
    {
        $methodMapper = new Elv();
        $methodMapper->mapMethodDataToPayment(
            $this->quoteTransfer,
            $this->payment
        );

        $this->testAbstractMapMethodDataToPayment();

        $this->assertEquals('bic', $this->payment->getBankAccountBic());
        $this->assertEquals('acchold', $this->payment->getBankAccountHolder());
        $this->assertEquals('iban', $this->payment->getBankAccountIban());
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function mockQuoteTransfer()
    {
        $quoteTransfer = parent::mockQuoteTransfer();

        $paymentTransfer = new RatepayPaymentElvTransfer();
        $paymentTransfer = $this->mockPaymentTransfer($paymentTransfer);

        $quoteTransfer->getPayment()
            ->setRatepayElv($paymentTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\RatepayPaymentElvTransfer $paymentTransfer
     *
     * @return \Generated\Shared\Transfer\RatepayPaymentElvTransfer
     */
    protected function mockPaymentTransfer($paymentTransfer)
    {
        $paymentTransfer = parent::mockPaymentTransfer($paymentTransfer);
        $paymentTransfer->setBankAccountBic("bic");
        $paymentTransfer->setBankAccountHolder("acchold");
        $paymentTransfer->setBankAccountIban("iban");

        return $paymentTransfer;
    }

}
