<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Ratepay\Business\Api\Converter;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RatepayPaymentElvTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Spryker\Zed\Ratepay\Business\Api\Mapper\MapperFactory;

abstract class AbstractMapperTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var \Spryker\Zed\Ratepay\Business\Api\Mapper\MapperFactory
     */
    protected $mapperFactory;

    protected function setUp()
    {
        parent::setUp();

        $this->mapperFactory = new MapperFactory();
    }

    abstract function testMapper();

    /**
     * @return QuoteTransfer
     */
    protected function mockQuoteTransfer()
    {
        $total = new TotalsTransfer();
        $total->setGrandTotal(9900)
            ->setExpenseTotal(8900)
        ;

        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->setTotals($total);

        return $quoteTransfer;
    }

    /**
     * @return RatepayPaymentElvTransfer
     */
    protected function mockPaymentElvTransfer()
    {
        $ratepayPaymentTransfer = new RatepayPaymentElvTransfer();
        $ratepayPaymentTransfer->setBankAccountIban("iban")
            ->setBankAccountBic("bic")
            ->setBankAccountHolder("holder")
            ->setCurrencyIso3("iso3")
        ;

        return $ratepayPaymentTransfer;
    }
    

}
