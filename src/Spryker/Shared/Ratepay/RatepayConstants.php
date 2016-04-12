<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Ratepay;

interface RatepayConstants
{

    const RATEPAY = 'ratepay';

    const PROFILE_ID = 'RATEPAY_PROFILE_ID';
    const SECURITY_CODE = 'RATEPAY_SECURITY_CODE';
    const SNIPPET_ID = 'RATEPAY_SNIPPET_ID';
    const SHOP_ID = 'RATEPAY_SHOP_ID';
    const SYSTEM_ID = 'RATEPAY_SYSTEM_ID';

    /**
     * API modes urls.
     */
    const API_TEST_URL = 'RATEPAY_API_TEST_URL';
    const API_LIVE_URL = 'RATEPAY_API_LIVE_URL';

    /**
     * Integration mode: test/live.
     */
    const MODE = 'RATEPAY_MODE';
    const MODE_LIVE = 'live';
    const MODE_TEST = 'test';

    /**
     * Payment submethods.
     */
    const METHOD_INVOICE = 'INVOICE';
    const METHOD_ELV = 'ELV';
    const METHOD_PREPAYMENT = 'PREPAYMENT';
    const METHOD_INSTALLMENT = 'INSTALLMENT';

    /**
     * Genders.
     */
    const GENDER_MALE = 'M';
    const GENDER_FEMALE = 'F';

    /**
     * User Agent of Spryker client.
     */
    const CLIENT_VERSION = '1.0';
    const CLIENT_NAME = 'Spryker Connector';

    /**
     * Monolog logger configuration.
     */
    const LOGGER_STREAM_OUTPUT = '/tmp/ratepay.log';

}
