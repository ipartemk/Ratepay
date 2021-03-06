<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Ratepay\Business\Api\Converter;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RatepayPaymentElvTransfer;
use Generated\Shared\Transfer\RatepayPaymentInstallmentTransfer;
use Generated\Shared\Transfer\RatepayRequestTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Spryker\Zed\Ratepay\Business\Api\Mapper\MapperFactory;

/**
 * Class AbstractMapperTest
 * @package Unit\Spryker\Zed\Ratepay\Business\Api\Converter
 */
abstract class AbstractMapperTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var \Spryker\Zed\Ratepay\Business\Api\Mapper\MapperFactory
     */
    protected $mapperFactory;

    /**
     * @var \Generated\Shared\Transfer\RatepayRequestTransfer
     */
    protected $requestTransfer;

    protected function setUp()
    {
        parent::setUp();

        $this->requestTransfer = new RatepayRequestTransfer();
        $this->mapperFactory = new MapperFactory($this->requestTransfer);
    }

    abstract public function testMapper();

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function mockQuoteTransfer()
    {
        $total = new TotalsTransfer();
        $total->setGrandTotal(9900)
            ->setExpenseTotal(8900);

        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->setTotals($total)
            ->setCustomer($this->mockCustomerTransfer())
            ->setBillingAddress($this->mockAddressTransfer())
            ->setShippingAddress($this->mockAddressTransfer())
            ->setPayment(new PaymentTransfer());

        return $quoteTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function mockCustomerTransfer()
    {
        $customerTransfer = new CustomerTransfer();
        $customerTransfer->setEmail("email@site.com");

        return $customerTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    protected function mockAddressTransfer()
    {
        $address = new AddressTransfer();
        $address->setFirstName("fn")
            ->setLastName("ln")
            ->setPhone("0491234567")
            ->setCity("Berlin")
            ->setIso2Code("iso2")
            ->setAddress1("addr1")
            ->setAddress2("addr2")
            ->setZipCode("zip");
        return $address;
    }

    /**
     * @return \Generated\Shared\Transfer\RatepayPaymentElvTransfer
     */
    protected function mockPaymentElvTransfer()
    {
        $ratepayPaymentTransfer = new RatepayPaymentElvTransfer();
        $ratepayPaymentTransfer->setBankAccountIban("iban")
            ->setBankAccountBic("bic")
            ->setBankAccountHolder("holder")
            ->setCurrencyIso3("iso3")
            ->setGender("m")
            ->setPhone("123456789")
            ->setDateOfBirth("1980-01-02")
            ->setIpAddress("127.1.2.3")
            ->setCustomerAllowCreditInquiry(true)
            ->setPaymentType("invoice");

        return $ratepayPaymentTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\RatepayPaymentInstallmentTransfer
     */
    protected function mockRatepayPaymentInstallmentTransfer()
    {
        $ratepayPaymentInstallmentTransfer = new RatepayPaymentInstallmentTransfer();
        $ratepayPaymentInstallmentTransfer
            ->setInstallmentCalculationType('calculation-by-rate')
            ->setInstallmentGrandTotalAmount(12570)
            ->setInstallmentRate(1200)
            ->setInstallmentInterestRate(14)
            ->setInstallmentLastRate(1450)
            ->setInstallmentMonth(3)
            ->setInterestRate(14)
            ->setInterestMonth(3)
            ->setInstallmentNumberRates(3)
            ->setInstallmentPaymentFirstDay(28)
            ->setInstallmentCalculationStart("2016-05-15")

            ->setBankAccountIban("iban")
            ->setBankAccountBic("bic")
            ->setBankAccountHolder("holder")
            ->setCurrencyIso3("iso3")
            ->setGender("m")
            ->setPhone("123456789")
            ->setDateOfBirth("1980-01-02")
            ->setIpAddress("127.1.2.3")
            ->setCustomerAllowCreditInquiry(true)
            ->setPaymentType("invoice")
            ->setDebitPayType("invoice");

        return $ratepayPaymentInstallmentTransfer;
    }

}
