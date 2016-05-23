<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Communication\Controller;

use Spryker\Zed\Application\Communication\Controller\AbstractController;

/**
 * @method \Spryker\Zed\Ratepay\Business\RatepayFacade getFacade()
 * @method \Spryker\Zed\Ratepay\Communication\RatepayCommunicationFactory getFactory()
 */
class ProfileController extends AbstractController
{

    /**
     * @return array
     */
    public function indexAction()
    {
        return $this->viewResponse([
            'profile' => $this->getFacade()->requestProfile(),
        ]);
    }

}
