<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Settings;

interface ModuleSettingsInterface
{
    public function getDocumentFooter(): string;

    public function getFilePrefix(): string;

    public function isForArchive(): bool;
}
