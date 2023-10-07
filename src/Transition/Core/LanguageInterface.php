<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Transition\Core;

interface LanguageInterface
{
    public function faForceSetTplLanguage(int $language): void;

    public function getTplLanguage(): int;
}
