<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Ratepay\Business\XmlElement;

use Spryker\Zed\Ratepay\Business\Api\SimpleXMLElement;

class SpecialCharactersTest extends \PHPUnit_Framework_TestCase
{

    protected $characters = array(
        "–" => "-",
        "´" => "'",
        "‹" => "<",
        "›" => ">",
        "‘" => "'",
        "’" => "'",
        "‚" => ",",
        "“" => '"',
        "”" => '"',
        "„" => '"',
        "‟" => '"',
        "•" => "-",
        "‒" => "-",
        "―" => "-",
        "—" => "-",
        "™" => "TM",
        "¼" => "1/4", 
        "½" => "1/2", 
        "¾" => "3/4"
    );

    /**
     * @return void
     */
    public function testSpecialCharacters()
    {
        $simpleXmlElement = new SimpleXMLElement('<root></root>');
        foreach ($this->characters as $character => $expected) {
            $this->assertEquals($expected, (string)$simpleXmlElement->addCDataChild('test', $character));
        }
    }
}