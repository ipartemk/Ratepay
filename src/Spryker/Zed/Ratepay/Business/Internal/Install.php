<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Internal;

use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\Installer\Business\Model\AbstractInstaller;
use Spryker\Zed\Ratepay\Dependency\Facade\RatepayToGlossaryInterface;
use Spryker\Zed\Ratepay\RatepayConfig;
use Symfony\Component\Yaml\Yaml;

class Install extends AbstractInstaller
{

    /**
     * @var \Spryker\Zed\Ratepay\Dependency\Facade\RatepayToGlossaryInterface
     */
    protected $glossary;

    /**
     * @var \Spryker\Zed\Price\PriceConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\Ratepay\Dependency\Facade\RatepayToGlossaryInterface $glossary
     * @param \Spryker\Zed\Ratepay\RatepayConfig $config
     */
    public function __construct(RatepayToGlossaryInterface $glossary, RatepayConfig $config)
    {
        $this->glossary = $glossary;
        $this->config = $config;
    }

    /**
     * @return array
     */
    public function install()
    {
        $fileName = $this->config->getTranslationFilePath();
        $translations = $this->parseYamlFile($fileName);

        return $this->installKeysAndTranslations($translations);
    }

    /**
     * @param string $filePath
     *
     * @return array
     */
    protected function parseYamlFile($filePath)
    {
        $yamlParser = new Yaml();

        return $yamlParser->parse(file_get_contents($filePath));
    }

    /**
     * @param array $translations
     *
     * @return array
     */
    protected function installKeysAndTranslations(array $translations)
    {
        $results = [];
        foreach ($translations as $keyName => $data) {
            $results[$keyName]['created'] = false;
            if (!$this->glossary->hasKey($keyName)) {
                $this->glossary->createKey($keyName);
                $results[$keyName]['created'] = true;
            }

            foreach ($data['translations'] as $localeName => $text) {
                $locale = new LocaleTransfer();
                $locale->setLocaleName($localeName);
                $results[$keyName]['translation'][$localeName]['text'] = $text;
                $results[$keyName]['translation'][$localeName]['created'] = false;
                $results[$keyName]['translation'][$localeName]['updated'] = false;

                if (!$this->glossary->hasTranslation($keyName, $locale)) {
                    $this->glossary->createAndTouchTranslation($keyName, $locale, $text, true);
                    $results[$keyName]['translation'][$localeName]['created'] = true;
                } else {
                    $this->glossary->updateAndTouchTranslation($keyName, $locale, $text, true);
                    $results[$keyName]['translation'][$localeName]['updated'] = true;
                }
            }
        }

        return $results;
    }

}
