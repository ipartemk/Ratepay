<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Ratepay\Business\Api\Response;

use Spryker\Zed\Ratepay\Business\Api\Converter\ConverterFactory;
use Spryker\Zed\Ratepay\Business\Api\Model\Response\BaseResponse;

class ConverterTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testConverterData()
    {
        $responseTransfer = $this->getResponseTransferObject((new Response)->getTestPaymentConfirmResponseData());

        //test instance.
        $this->assertInstanceOf('\Generated\Shared\Transfer\RatepayResponseTransfer', $responseTransfer);

        //test data.
        $this->assertEquals('Die Prüfung war erfolgreich. Vielen Dank, dass Sie die Zahlart Rechnung gewählt haben.', $responseTransfer->getCustomerMessage());

        $this->assertEquals('303', $responseTransfer->getReasonCode());
        $this->assertEquals('No RMS reason code', $responseTransfer->getReasonText());

        $this->assertEquals('400', $responseTransfer->getResultCode());
        $this->assertEquals('Transaction result successful', $responseTransfer->getResultText());

        $this->assertEquals('OK', $responseTransfer->getStatusCode());
        $this->assertEquals('Successfully', $responseTransfer->getStatusText());

        $this->assertEquals(true, $responseTransfer->getSuccessful());

        $this->assertEquals('58-201604122719694', $responseTransfer->getTransactionId());
        $this->assertEquals('5QTZ.2VWD.OMWW.9D3E', $responseTransfer->getTransactionShortId());
    }

    public function testResponseSuccessState()
    {
        $response = new Response;
        $successResponseTransfer = $this->getResponseTransferObject($response->getTestPaymentConfirmResponseData());
        $unSuccessResponseTransfer = $this->getResponseTransferObject($response->getTestPaymentConfirmUnsuccessResponseData());

        $this->assertEquals(true, $successResponseTransfer->getSuccessful());
        $this->assertNotEquals(true, $unSuccessResponseTransfer->getSuccessful());
    }

    /**
     * @param string $responseXml
     *
     * @return \Generated\Shared\Transfer\RatepayResponseTransfer
     */
    protected function getResponseTransferObject($responseXml)
    {
        $responseObject = new BaseResponse($responseXml);
        $converterFactory = new ConverterFactory();
        return $converterFactory
            ->getTransferObjectConverter($responseObject)
            ->convert();
    }

}
