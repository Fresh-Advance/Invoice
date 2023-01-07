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

    public function __construct(
        private ModuleSettingServiceInterface $moduleSettingService
    ) {
    }

    public function getDocumentFooter(): string
    {
        return $this->moduleSettingService
            ->getString(self::SETTING_DOCUMENT_FOOTER, Module::MODULE_ID)
            ->toString();
    }
}
