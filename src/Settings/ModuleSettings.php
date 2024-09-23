<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Settings;

use FreshAdvance\Invoice\Module;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Facade\ModuleSettingServiceInterface;

class ModuleSettings implements ModuleSettingsInterface
{
    public const SETTING_DOCUMENT_FILENAME_PREFIX = 'fa_invoice_FilenamePrefix';
    public const SETTING_DOCUMENT_IS_FOR_ARCHIVE = 'fa_invoice_IsForArchive';
    public const SETTING_INVOICE_NUMBER_FORMAT = 'fa_invoice_InvoiceNumberFormat';
    public const SETTING_INVOICE_DATE_FORMAT = 'fa_invoice_InvoiceDateFormat';

    public function __construct(
        private ModuleSettingServiceInterface $moduleSettingService
    ) {
    }

    public function getFilePrefix(): string
    {
        return $this->getStringSetting(self::SETTING_DOCUMENT_FILENAME_PREFIX);
    }

    public function getInvoiceNumberFormat(): string
    {
        return $this->getStringSetting(self::SETTING_INVOICE_NUMBER_FORMAT);
    }

    public function isForArchive(): bool
    {
        return $this->moduleSettingService->getBoolean(
            self::SETTING_DOCUMENT_IS_FOR_ARCHIVE,
            Module::MODULE_ID
        );
    }

    public function getInvoiceDateFormat(): string
    {
        return $this->getStringSetting(self::SETTING_INVOICE_DATE_FORMAT);
    }

    private function getStringSetting(string $key): string
    {
        return $this->moduleSettingService
            ->getString($key, Module::MODULE_ID)
            ->toString();
    }
}
