<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Service;

use OxidEsales\EshopCommunity\Internal\Framework\Module\Facade\ModuleSettingServiceInterface;
use FreshAdvance\Invoice\Module;

class ModuleSettings
{
    public const SETTING_DOCUMENT_FOOTER = 'fa_invoice_DocumentFooter';
    public const SETTING_DOCUMENT_FILENAME_PREFIX = 'fa_invoice_FilenamePrefix';

    public function __construct(
        private ModuleSettingServiceInterface $moduleSettingService
    ) {
    }

    public function getDocumentFooter(): string
    {
        return $this->getStringSetting(self::SETTING_DOCUMENT_FOOTER);
    }

    public function getFilePrefix(): string
    {
        return $this->getStringSetting(self::SETTING_DOCUMENT_FILENAME_PREFIX);
    }

    private function getStringSetting(string $key): string
    {
        return $this->moduleSettingService
            ->getString($key, Module::MODULE_ID)
            ->toString();
    }
}
