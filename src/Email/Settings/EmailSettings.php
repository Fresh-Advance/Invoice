<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Email\Settings;

use FreshAdvance\Invoice\Module;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Facade\ModuleSettingServiceInterface;

class EmailSettings implements EmailSettingsInterface
{
    public const SETTING_SEND_INVOICE_ON_USER_ORDER_EMAIL = 'fa_invoice_SendInvoiceOnUserOrderEmail';
    public const SETTING_SEND_INVOICE_ON_OWNER_ORDER_EMAIL = 'fa_invoice_SendInvoiceOnOwnerOrderEmail';
    public const SETTING_USER_ORDER_EMAIL_INVOICE_FILENAME_FORMAT = 'fa_invoice_UserOrderEmailInvoiceFilenameFormat';
    public const SETTING_OWNER_ORDER_EMAIL_INVOICE_FILENAME_FORMAT = 'fa_invoice_OwnerOrderEmailInvoiceFilenameFormat';

    public function __construct(
        readonly private ModuleSettingServiceInterface $moduleSettingService
    ) {
    }

    public function isSendInvoiceOnUserOrderEmailActive(): bool
    {
        return $this->moduleSettingService->getBoolean(
            self::SETTING_SEND_INVOICE_ON_USER_ORDER_EMAIL,
            Module::MODULE_ID,
        );
    }

    public function isSendInvoiceOnOwnerOrderEmailActive(): bool
    {
        return $this->moduleSettingService->getBoolean(
            self::SETTING_SEND_INVOICE_ON_OWNER_ORDER_EMAIL,
            Module::MODULE_ID,
        );
    }

    public function getUserOrderEmailInvoiceFilenameFormat(): string
    {
        return $this->moduleSettingService->getString(
            self::SETTING_USER_ORDER_EMAIL_INVOICE_FILENAME_FORMAT,
            Module::MODULE_ID,
        )->toString();
    }

    public function getOwnerOrderEmailInvoiceFilenameFormat(): string
    {
        return $this->moduleSettingService->getString(
            self::SETTING_OWNER_ORDER_EMAIL_INVOICE_FILENAME_FORMAT,
            Module::MODULE_ID,
        )->toString();
    }
}
