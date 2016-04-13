<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Ratepay\Business\Api\Response;

use Spryker\Zed\Ratepay\Business\Api\Converter\Converter;
use Spryker\Zed\Ratepay\Business\Api\Model\Response\BaseResponse;

class ConverterTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testConverterData()
    {
        $responseTransfer = $this->getResponseTransferObject($this->getTestPaymentConfirmResponseData());

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
        $successResponseTransfer = $this->getResponseTransferObject($this->getTestPaymentConfirmResponseData());
        $unSuccessResponseTransfer = $this->getResponseTransferObject($this->getTestPaymentConfirmUnsuccessResponseData());

        $this->assertEquals(true, $successResponseTransfer->getSuccessful());
        $this->assertNotEquals(true, $unSuccessResponseTransfer->getSuccessful());
    }

    /**
     * @param string $responseXml
     *
     * @return \Generated\Shared\Transfer\RatepayResponseTransfer
     */
    private function getResponseTransferObject($responseXml)
    {
        $responseObject = new BaseResponse($responseXml);
        $exporter = new Converter();
        return $exporter->responseToTransferObject($responseObject);
    }

    /**
     * @return string
     */
    private function getTestPaymentConfirmResponseData()
    {
        return
            '<response xmlns="urn://www.ratepay.com/payment/1_0" version="1.0">
                <head>
                    <system-id>Spryker www.spryker.dev</system-id>
                    <transaction-id>58-201604122719694</transaction-id>
                    <transaction-short-id>5QTZ.2VWD.OMWW.9D3E</transaction-short-id>
                    <operation>PAYMENT_CONFIRM</operation>
                    <response-type>PAYMENT_PERMISSION</response-type>
                    <external />
                    <processing>
                        <timestamp>2016-04-12T16:27:33.000</timestamp>
                        <status code="OK">Successfully</status>
                        <reason code="303">No RMS reason code</reason>
                        <result code="400">Transaction result successful</result>
                        <customer-message>Die Prüfung war erfolgreich. Vielen Dank, dass Sie die Zahlart Rechnung gewählt haben.</customer-message>
                    </processing>
                </head>
                <content />
            </response>';
    }


    /**
     * @return string
     */
    private function getTestPaymentConfirmUnsuccessResponseData()
    {
        return
            '<response xmlns="urn://www.ratepay.com/payment/1_0" version="1.0">
                <head>
                    <system-id>Spryker www.spryker.dev</system-id>
                    <transaction-id>58-201604122719694</transaction-id>
                    <transaction-short-id>5QTZ.2VWD.OMWW.9D3E</transaction-short-id>
                    <operation>PAYMENT_CONFIRM</operation>
                    <response-type>PAYMENT_PERMISSION</response-type>
                    <external />
                    <processing>
                        <timestamp>2016-04-12T16:27:33.000</timestamp>
                        <status code="OK">Successfully</status>
                        <reason code="303">XXXX</reason>
                        <result code="401">XXXX</result>
                        <customer-message>XXXXXXXX</customer-message>
                    </processing>
                </head>
                <content />
            </response>';
    }

}
