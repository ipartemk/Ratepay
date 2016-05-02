<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Ratepay\Business\Expander;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Ratepay\Business\Expander\ProductExpander;

class ProductExpanderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Mockery;
     */
    protected $mockery;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->mockery = new \Mockery();
    }

    public function testExpandItems()
    {
        $item = new ItemTransfer();
        $item->setSku('sku1');

        $change = new CartChangeTransfer();
        $change->addItem($item);

        $productExpander = new ProductExpander($this->mockRatepayToProductBridge());
        $productExpander->expandItems($change);

        $item = $change->getItems()[0];
        $this->assertEquals('sd', $item->getDescriptionAddition());
        $this->assertEquals('ld', $item->getDescription());
    }

    /**
     * @return \Spryker\Zed\Ratepay\Dependency\Facade\RatepayToProductBridge
     */
    protected function mockRatepayToProductBridge()
    {
        $localizedAttribute = new LocalizedAttributesTransfer();
        $localizedAttribute->setAttributes([
            'short_description' => "sd",
            'long_description' => "ld",
        ]);

        $product = new ProductConcreteTransfer();
        $product->addLocalizedAttributes($localizedAttribute);

        $ratepayToProductBridge = $this->mockery->mock('\Spryker\Zed\Ratepay\Dependency\Facade\RatepayToProductBridge');
        $ratepayToProductBridge->shouldReceive('getProductConcrete')
            ->andReturn($product);

        return $ratepayToProductBridge;
    }

    protected function tearDown()
    {
        parent::tearDown();

        $this->mockery->close();
    }

}
