<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Settings;

use OxidEsales\EshopCommunity\Internal\Framework\Module\Facade\ModuleSettingServiceInterface;
use FreshAdvance\Invoice\Module;

class ModuleSettings implements ModuleSettingsInterface
{
    public const SETTING_DOCUMENT_FOOTER = 'fa_invoice_DocumentFooter';
    public const SETTING_DOCUMENT_FILENAME_PREFIX = 'fa_invoice_FilenamePrefix';
    public const SETTING_DOCUMENT_IS_FOR_ARCHIVE = 'fa_invoice_IsForArchive';

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

    public function isForArchive(): bool
    {
        return $this->moduleSettingService->getBoolean(
            self::SETTING_DOCUMENT_IS_FOR_ARCHIVE,
            Module::MODULE_ID
        );
    }

    private function getStringSetting(string $key): string
    {
        return $this->moduleSettingService
            ->getString($key, Module::MODULE_ID)
            ->toString();
    }
}
