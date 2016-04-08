<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\Ratepay\Business\Order;

use Spryker\Shared\Ratepay\RatepayConstants;
use Spryker\Zed\Ratepay\Business\Order\MethodMapper\Elv as ElvMapper;
use Spryker\Zed\Ratepay\Business\Order\MethodMapper\Invoice as InvoiceMapper;
use Spryker\Zed\Ratepay\Business\Order\MethodMapper\Prepayment as PrepaymentMapper;
use Spryker\Zed\Ratepay\Business\Order\MethodMapper\Installment as InstallmentMapper;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Ratepay\Business\Order\MethodMapper\Transaction\Transaction;

class MethodMapperFactory extends AbstractBusinessFactory
{

    /**
     * return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getQuoteTransfer()
    {
        return null;
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Order\MethodMapper\Transaction\TransactionInterface
     */
    public function createPaymentTransactionHandler()
    {
        $paymentTransactionHandler = new Transaction();
        $paymentTransactionHandler->registerMethodMapper($this->createElvMethodMapper());
        $paymentTransactionHandler->registerMethodMapper($this->createInstallmentMethodMapper());
        $paymentTransactionHandler->registerMethodMapper($this->createInvoiceMethodMapper());
        $paymentTransactionHandler->registerMethodMapper($this->createPrepaymentMethodMapper());
        
        return $paymentTransactionHandler;
    }

    protected function createElvMethodMapper()
    {
        return new ElvMapper();
    }
    
    protected function createInvoiceMethodMapper()
    {
        return new InvoiceMapper();
    }

    protected function createPrepaymentMethodMapper()
    {
        return new PrepaymentMapper();
    }

    protected function createInstallmentMethodMapper()
    {
        return new InstallmentMapper();
    }

}