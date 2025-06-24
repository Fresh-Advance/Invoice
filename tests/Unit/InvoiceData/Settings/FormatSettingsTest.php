<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Tests\Unit\InvoiceData\Settings;

use FreshAdvance\Invoice\InvoiceData\Settings\FormatSettings;
use FreshAdvance\Invoice\Module;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Facade\ModuleSettingServiceInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\String\UnicodeString;

final class FormatSettingsTest extends TestCase
{
    public function testGetFileNameFormat(): void
    {
        $value = 'someValue';

        $mssMock = $this->createMock(ModuleSettingServiceInterface::class);
        $mssMock->method('getString')->willReturnMap([
            [FormatSettings::SETTING_DOCUMENT_FILENAME_FORMAT, Module::MODULE_ID, new UnicodeString($value)]
        ]);

        $sut = new FormatSettings($mssMock);
        $this->assertSame($value, $sut->getFileNameFormat());
    }

    public function testGetInvoiceNumberFormat(): void
    {
        $value = uniqid();

        $mssMock = $this->createMock(ModuleSettingServiceInterface::class);
        $mssMock->method('getString')->willReturnMap([
            [FormatSettings::SETTING_INVOICE_NUMBER_FORMAT, Module::MODULE_ID, new UnicodeString($value)]
        ]);

        $sut = new FormatSettings($mssMock);
        $this->assertSame($value, $sut->getInvoiceNumberFormat());
    }

    public function testGetInvoiceDateFormat(): void
    {
        $value = uniqid();

        $mssMock = $this->createMock(ModuleSettingServiceInterface::class);
        $mssMock->method('getString')->willReturnMap([
            [FormatSettings::SETTING_INVOICE_DATE_FORMAT, Module::MODULE_ID, new UnicodeString($value)]
        ]);

        $sut = new FormatSettings($mssMock);
        $this->assertSame($value, $sut->getInvoiceDateFormat());
    }
}
