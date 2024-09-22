<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Document\Settings;

use FreshAdvance\Invoice\Module;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Facade\ModuleSettingServiceInterface;

class DocumentLayoutSettings implements DocumentLayoutSettingsInterface
{
    public const SETTING_MARGIN_TOP = 'fa_invoice_MarginTop';
    public const SETTING_MARGIN_BOTTOM = 'fa_invoice_MarginBottom';
    public const SETTING_MARGIN_LEFT = 'fa_invoice_MarginLeft';
    public const SETTING_MARGIN_RIGHT = 'fa_invoice_MarginRight';

    public const SETTING_DOCUMENT_HEADER = 'fa_invoice_DocumentHeader';
    public const SETTING_DOCUMENT_FOOTER = 'fa_invoice_DocumentFooter';

    public function __construct(
        protected ModuleSettingServiceInterface $moduleSettingService,
    ) {
    }

    public function getMarginTop(): string
    {
        return $this->getStringSettingValue(self::SETTING_MARGIN_TOP);
    }

    public function getMarginBottom(): string
    {
        return $this->getStringSettingValue(self::SETTING_MARGIN_BOTTOM);
    }

    public function getMarginLeft(): string
    {
        return $this->getStringSettingValue(self::SETTING_MARGIN_LEFT);
    }

    public function getMarginRight(): string
    {
        return $this->getStringSettingValue(self::SETTING_MARGIN_RIGHT);
    }

    public function getDocumentHeader(): string
    {
        return $this->getStringSettingValue(self::SETTING_DOCUMENT_HEADER);
    }

    public function getDocumentFooter(): string
    {
        return $this->getStringSettingValue(self::SETTING_DOCUMENT_FOOTER);
    }

    private function getStringSettingValue(string $settingKey): string
    {
        return $this->moduleSettingService
            ->getString($settingKey, Module::MODULE_ID)
            ->toString();
    }
}
