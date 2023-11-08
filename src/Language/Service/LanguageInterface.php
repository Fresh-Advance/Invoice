<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Language\Service;

interface LanguageInterface
{
    public function forceSetTplLanguage(int $language): void;

    public function getTplLanguage(): int;

    public function getLanguageAbbreviation(): string;
}
