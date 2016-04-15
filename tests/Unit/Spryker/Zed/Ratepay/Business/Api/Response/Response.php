<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Ratepay\Business\Api\Response;

class Response
{

    /**
     * @return string
     */
    public static function getTestPaymentConfirmResponseData()
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
    public static function getTestPaymentConfirmUnsuccessResponseData()
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
