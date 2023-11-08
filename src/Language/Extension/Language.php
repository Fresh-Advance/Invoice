<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Language\Extension;

/**
 * @mixin \OxidEsales\Eshop\Core\Language
 */
class Language extends Language_parent
{
    protected $_iTplLanguageId = null;

    public function faForceSetTplLanguage(int $languageId): void
    {
        $this->_iTplLanguageId = $languageId;
    }
}
