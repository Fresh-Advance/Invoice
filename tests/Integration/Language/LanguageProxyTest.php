<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Tests\Integration\Language;

use FreshAdvance\Invoice\Language\Extension\Language;
use PHPUnit\Framework\TestCase;

/**
 * @covers \FreshAdvance\Invoice\Language\Extension\Language
 * @covers \FreshAdvance\Invoice\Language\Service\LanguageProxy
 */
class LanguageProxyTest extends TestCase
{
    public function testForceLanguageIdSetter(): void
    {
        $languageId = 3;

        /** @var \OxidEsales\Eshop\Core\Language $languageMock */
        $languageMock = $this->createPartialMock(Language::class, []);

        $sut = new \FreshAdvance\Invoice\Language\Service\LanguageProxy($languageMock);
        $sut->forceSetTplLanguage($languageId);

        $this->assertSame($languageId, $sut->getTplLanguage());
    }

    public function testGetLanguageAbbreviation(): void
    {
        $languageId = 3;
        $abbreviation = 'someAbbr';

        /** @var \OxidEsales\Eshop\Core\Language $languageMock */
        $languageMock = $this->createPartialMock(Language::class, ['getLanguageAbbr', 'getTplLanguage']);
        $languageMock->method('getLanguageAbbr')->with($languageId)->willReturn($abbreviation);
        $languageMock->method('getTplLanguage')->willReturn($languageId);

        $sut = new \FreshAdvance\Invoice\Language\Service\LanguageProxy($languageMock);

        $this->assertSame($abbreviation, $sut->getLanguageAbbreviation());
    }
}
