<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Shared\Ratepay\RatepayConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class RatepayConfig extends AbstractBundleConfig
{

    /**
     * @return string
     */
    public function getTransactionGatewayUrl()
    {
        if ($this->get(RatepayConstants::MODE) === RatepayConstants::MODE_LIVE) {
            return $this->get(RatepayConstants::API_LIVE_URL);
        }
        return $this->get(RatepayConstants::API_TEST_URL);
    }

    /**
     * @return string
     */
    public function getProfileId()
    {
        return $this->get(RatepayConstants::PROFILE_ID);
    }

    /**
     * @return string
     */
    public function getSecurityCode()
    {
        return $this->get(RatepayConstants::SECURITY_CODE);
    }

    /**
     * @return string
     */
    public function getSystemId()
    {
        return $this->get(RatepayConstants::SYSTEM_ID);
    }

    /**
     * @return string
     */
    public function getSnippedId()
    {
        return $this->get(RatepayConstants::SNIPPET_ID);
    }

    /**
     * @return string
     */
    public function getShopId()
    {
        return $this->get(RatepayConstants::SHOP_ID);
    }

    /**
     * @return string
     */
    public function getTranslationFilePath()
    {
        return $this->get(ApplicationConstants::APPLICATION_SPRYKER_ROOT)
            . '/Ratepay/src/Spryker/Zed/Ratepay/Business/Internal/glossary.yml';
    }

}
