<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace Email\Settings;

use FreshAdvance\Invoice\Email\Settings\EmailSettings;
use FreshAdvance\Invoice\Module;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Facade\ModuleSettingServiceInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\String\UnicodeString;

class EmailSettingsTest extends \PHPUnit\Framework\TestCase
{
    #[DataProvider('booleanDataProvider')]
    public function testIsSendInvoiceOnUserOrderEmailActive(bool $value): void
    {
        $mssMock = $this->createMock(ModuleSettingServiceInterface::class);
        $mssMock->method('getBoolean')->willReturnMap([
            [EmailSettings::SETTING_SEND_INVOICE_ON_USER_ORDER_EMAIL, Module::MODULE_ID, $value]
        ]);

        $sut = new EmailSettings($mssMock);
        $this->assertSame($value, $sut->isSendInvoiceOnUserOrderEmailActive());
    }

    #[DataProvider('booleanDataProvider')]
    public function testIsSendInvoiceOnOwnerOrderEmailActive(bool $value): void
    {
        $mssMock = $this->createMock(ModuleSettingServiceInterface::class);
        $mssMock->method('getBoolean')->willReturnMap([
            [EmailSettings::SETTING_SEND_INVOICE_ON_OWNER_ORDER_EMAIL, Module::MODULE_ID, $value]
        ]);

        $sut = new EmailSettings($mssMock);
        $this->assertSame($value, $sut->isSendInvoiceOnOwnerOrderEmailActive());
    }

    public static function booleanDataProvider(): array
    {
        return [
            [true],
            [false]
        ];
    }

    public function testGetUserOrderEmailInvoiceFilenameFormat(): void
    {
        $value = uniqid();

        $mssMock = $this->createMock(ModuleSettingServiceInterface::class);
        $mssMock->method('getString')->willReturnMap([
            [
                EmailSettings::SETTING_USER_ORDER_EMAIL_INVOICE_FILENAME_FORMAT,
                Module::MODULE_ID,
                new UnicodeString($value)
            ]
        ]);

        $sut = new EmailSettings($mssMock);
        $this->assertSame($value, $sut->getUserOrderEmailInvoiceFilenameFormat());
    }

    public function testGetOwnerOrderEmailInvoiceFilenameFormat(): void
    {
        $value = uniqid();

        $mssMock = $this->createMock(ModuleSettingServiceInterface::class);
        $mssMock->method('getString')->willReturnMap([
            [
                EmailSettings::SETTING_OWNER_ORDER_EMAIL_INVOICE_FILENAME_FORMAT,
                Module::MODULE_ID,
                new UnicodeString($value)
            ]
        ]);

        $sut = new EmailSettings($mssMock);
        $this->assertSame($value, $sut->getOwnerOrderEmailInvoiceFilenameFormat());
    }
}
