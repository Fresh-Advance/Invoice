<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace Transition\Core;

use FreshAdvance\Invoice\Transition\Core\Language;
use FreshAdvance\Invoice\Transition\Core\LanguageProxy;
use PHPUnit\Framework\TestCase;

/**
 * @covers \FreshAdvance\Invoice\Transition\Core\Language
 * @covers \FreshAdvance\Invoice\Transition\Core\LanguageProxy
 */
class LanguageProxyTest extends TestCase
{
    public function testForceLanguageIdSetter(): void
    {
        $languageId = 3;

        /** @var \OxidEsales\Eshop\Core\Language $languageMock */
        $languageMock = $this->createPartialMock(Language::class, []);

        $sut = new LanguageProxy($languageMock);
        $sut->forceSetTplLanguage($languageId);

        $this->assertSame($languageId, $sut->getTplLanguage());
    }
}
