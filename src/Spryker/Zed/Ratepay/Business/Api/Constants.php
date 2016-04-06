<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Api;

class Constants
{

    /**
     * Request model types.
     */
    const REQUEST_MODEL_HEAD = 'HEAD';
    const REQUEST_MODEL_CUSTOMER = 'CUSTOMER';
    const REQUEST_MODEL_PAYMENT = 'PAYMENT';
    const REQUEST_MODEL_BASKET = 'BASKET';
    const REQUEST_MODEL_PAYMENT_INIT = 'PAYMENT_INIT';
    const REQUEST_MODEL_PAYMENT_REQUEST = 'PAYMENT_REQUEST';
    const REQUEST_MODEL_PAYMENT_CONFIRM = 'PAYMENT_CONFIRM';
    const REQUEST_MODEL_CONFIRM_DELIVER = 'CONFIRM_DELIVER';

    const REQUEST_HEADER_CONTENT_TYPE = 'text/xml; charset=UTF8';

    const REQUEST_MODEL_ADDRESS_TYPE_BILLING = 'BILLLING';
    const REQUEST_MODEL_ADDRESS_TYPE_SHIPPING = 'SHIPPING';
    const REQUEST_MODEL_ADDRESS_TYPE_REGISTRY = 'REGISTRY';

}
