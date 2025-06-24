<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\InvoiceData\Settings;

use FreshAdvance\Invoice\Module;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Facade\ModuleSettingServiceInterface;

class FormatSettings implements FormatSettingsInterface
{
    public const SETTING_DOCUMENT_FILENAME_FORMAT = 'fa_invoice_FilenameFormat';
    public const SETTING_INVOICE_NUMBER_FORMAT = 'fa_invoice_InvoiceNumberFormat';
    public const SETTING_INVOICE_DATE_FORMAT = 'fa_invoice_InvoiceDateFormat';

    public function __construct(
        readonly private ModuleSettingServiceInterface $moduleSettingService
    ) {
    }

    public function getFileNameFormat(): string
    {
        return $this->getStringSetting(self::SETTING_DOCUMENT_FILENAME_FORMAT);
    }

    public function getInvoiceNumberFormat(): string
    {
        return $this->getStringSetting(self::SETTING_INVOICE_NUMBER_FORMAT);
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
