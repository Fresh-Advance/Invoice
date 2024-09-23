<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Tests\Unit\Settings;

use FreshAdvance\Invoice\Module;
use FreshAdvance\Invoice\Settings\ModuleSettings;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Facade\ModuleSettingService;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Facade\ModuleSettingServiceInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\String\UnicodeString;

/**
 * @covers \FreshAdvance\Invoice\Settings\ModuleSettings
 */
final class ModuleSettingsTest extends TestCase
{
    public function testGetFilenamePrefix(): void
    {
        $value = 'someValue';

        $mssMock = $this->createMock(ModuleSettingServiceInterface::class);
        $mssMock->method('getString')->willReturnMap([
            [ModuleSettings::SETTING_DOCUMENT_FILENAME_PREFIX, Module::MODULE_ID, new UnicodeString($value)]
        ]);

        $sut = new ModuleSettings($mssMock);
        $this->assertSame($value, $sut->getFilePrefix());
    }

    /**
     * @dataProvider booleanDataProvider
     */
    public function testIsForArchive(bool $value): void
    {
        $mssMock = $this->createMock(ModuleSettingServiceInterface::class);
        $mssMock->method('getBoolean')->willReturnMap([
            [ModuleSettings::SETTING_DOCUMENT_IS_FOR_ARCHIVE, Module::MODULE_ID, $value]
        ]);

        $sut = new ModuleSettings($mssMock);
        $this->assertSame($value, $sut->isForArchive());
    }

    public function testGetInvoiceNumberFormat(): void
    {
        $value = uniqid();

        $mssMock = $this->createMock(ModuleSettingServiceInterface::class);
        $mssMock->method('getString')->willReturnMap([
            [ModuleSettings::SETTING_INVOICE_NUMBER_FORMAT, Module::MODULE_ID, new UnicodeString($value)]
        ]);

        $sut = new ModuleSettings($mssMock);
        $this->assertSame($value, $sut->getInvoiceNumberFormat());
    }

    public function testGetInvoiceDateFormat(): void
    {
        $value = uniqid();

        $mssMock = $this->createMock(ModuleSettingServiceInterface::class);
        $mssMock->method('getString')->willReturnMap([
            [ModuleSettings::SETTING_INVOICE_DATE_FORMAT, Module::MODULE_ID, new UnicodeString($value)]
        ]);

        $sut = new ModuleSettings($mssMock);
        $this->assertSame($value, $sut->getInvoiceDateFormat());
    }

    public function booleanDataProvider(): array
    {
        return [
            [true],
            [false]
        ];
    }
}
