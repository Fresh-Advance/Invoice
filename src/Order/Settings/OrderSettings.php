<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Order\Settings;

use FreshAdvance\Invoice\Module;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Facade\ModuleSettingServiceInterface;

class OrderSettings implements OrderSettingsInterface
{
    public const SETTING_INVOICE_NUMBER_UPDATE = 'fa_invoice_InvoiceNumberUpdate';

    public function __construct(
        private ModuleSettingServiceInterface $moduleSettingService,
    ) {
    }

    public function isOrderInvoiceNumberUpdateActive(): bool
    {
        return $this->moduleSettingService->getBoolean(
            self::SETTING_INVOICE_NUMBER_UPDATE,
            Module::MODULE_ID
        );
    }
}
