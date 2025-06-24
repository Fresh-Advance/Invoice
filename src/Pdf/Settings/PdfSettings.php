<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Pdf\Settings;

use FreshAdvance\Invoice\Module;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Facade\ModuleSettingServiceInterface;

class PdfSettings implements PdfSettingsInterface
{
    public const SETTING_DOCUMENT_IS_FOR_ARCHIVE = 'fa_invoice_IsForArchive';

    public function __construct(
        private readonly ModuleSettingServiceInterface $moduleSettingService,
    ) {
    }

    public function isForArchive(): bool
    {
        return $this->moduleSettingService->getBoolean(
            self::SETTING_DOCUMENT_IS_FOR_ARCHIVE,
            Module::MODULE_ID
        );
    }
}
