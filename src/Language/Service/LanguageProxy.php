<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Language\Service;

use FreshAdvance\Invoice\Language\Service\LanguageInterface;
use FreshAdvance\Invoice\Language\Extension\Language;

class LanguageProxy implements LanguageInterface
{
    /** @var \FreshAdvance\Invoice\Language\Extension\Language $language */
    private $language;

    public function __construct(\OxidEsales\Eshop\Core\Language $language)
    {
        /** @var \FreshAdvance\Invoice\Language\Extension\Language $language */
        $this->language = $language;
    }

    public function forceSetTplLanguage(int $language): void
    {
        $this->language->faForceSetTplLanguage($language);
    }

    public function getTplLanguage(): int
    {
        return (int)$this->language->getTplLanguage();
    }

    public function getLanguageAbbreviation(): string
    {
        return (string)$this->language->getLanguageAbbr($this->getTplLanguage());
    }
}
