<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Api\Mapper;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RatepayPaymentElvTransfer;
use Generated\Shared\Transfer\RatepayPaymentInstallmentTransfer;
use Spryker\Zed\Ratepay\Business\Api\Model\Parts\BankAccount;

class BankAccountMapper extends BaseMapper
{

    /**
     * @var QuoteTransfer
     */
    protected $quoteTransfer;

    /**
     * @var RatepayPaymentElvTransfer|RatepayPaymentInstallmentTransfer
     */
    protected $ratepayPaymentTransfer;

    /**
     * @var BankAccount
     */
    protected $bankAccount;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Spryker\Shared\Transfer\TransferInterface $ratepayPaymentTransfer
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Parts\BankAccount $bankAccount
     */
    public function __construct(
        QuoteTransfer $quoteTransfer,
        $ratepayPaymentTransfer,
        BankAccount $bankAccount
    )
    {
        $this->quoteTransfer = $quoteTransfer;
        $this->ratepayPaymentTransfer = $ratepayPaymentTransfer;
        $this->bankAccount = $bankAccount;
    }

    /**
     * @return void
     */
    public function map()
    {
        $this->bankAccount->setOwner($this->ratepayPaymentTransfer->getBankAccountHolder());
        $this->bankAccount->setIban($this->ratepayPaymentTransfer->getBankAccountIban());
        $this->bankAccount->setBicSwift($this->ratepayPaymentTransfer->getBankAccountBic());
    }

}
