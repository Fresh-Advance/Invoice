<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Transition\Core;

class LanguageProxy implements LanguageInterface
{
    /** @var Language $language */
    private $language;

    public function __construct(\OxidEsales\Eshop\Core\Language $language)
    {
        /** @var Language $language */
        $this->language = $language;
    }

    public function faForceSetTplLanguage(int $language): void
    {
        $this->language->faForceSetTplLanguage($language);
    }

    public function getTplLanguage(): int
    {
        return (int)$this->language->getTplLanguage();
    }
}
