<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Tests\Unit\Order\Settings;

use FreshAdvance\Invoice\Module;
use FreshAdvance\Invoice\Order\Settings\OrderSettings;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Facade\ModuleSettingServiceInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \FreshAdvance\Invoice\Order\Settings\OrderSettings
 */
final class OrderSettingsTest extends TestCase
{
    /**
     * @dataProvider booleanDataProvider
     */
    public function testUpdateOrderInvoiceNumberOnInvoiceGeneration(bool $value): void
    {
        $mssMock = $this->createMock(ModuleSettingServiceInterface::class);
        $mssMock->method('getBoolean')->willReturnMap([
            [OrderSettings::SETTING_INVOICE_NUMBER_UPDATE, Module::MODULE_ID, $value]
        ]);

        $sut = new OrderSettings($mssMock);
        $this->assertSame($value, $sut->isOrderInvoiceNumberUpdateActive());
    }

    public function booleanDataProvider(): array
    {
        return [
            [true],
            [false]
        ];
    }
}
