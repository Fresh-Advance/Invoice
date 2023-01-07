<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Tests\Unit\Service;

use OxidEsales\EshopCommunity\Internal\Framework\Module\Facade\ModuleSettingService;
use FreshAdvance\Invoice\Module;
use FreshAdvance\Invoice\Service\ModuleSettings;
use PHPUnit\Framework\TestCase;
use Symfony\Component\String\UnicodeString;

final class ModuleSettingsTest extends TestCase
{
    public function testGetDocumentFooter(): void
    {
        $value = 'someValue';

        $mssMock = $this->createPartialMock(ModuleSettingService::class, ['getString']);
        $mssMock->method('getString')->willReturnMap([
            [ModuleSettings::SETTING_DOCUMENT_FOOTER, Module::MODULE_ID, new UnicodeString($value)]
        ]);

        $sut = new ModuleSettings($mssMock);
        $this->assertSame($value, $sut->getDocumentFooter());
    }
}
