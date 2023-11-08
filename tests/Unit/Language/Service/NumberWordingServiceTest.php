<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Tests\Unit\Language\Service;

use FreshAdvance\Invoice\Language\Service\LanguageInterface;
use FreshAdvance\Invoice\Language\Service\NumberWordingService;
use NumberToWords\CurrencyTransformer\CurrencyTransformer;
use NumberToWords\NumberToWords;
use PHPUnit\Framework\TestCase;

/**
 * @covers \FreshAdvance\Invoice\Language\Service\NumberWordingService
 */
class NumberWordingServiceTest extends TestCase
{
    public function testSomething(): void
    {
        $currencyTransformer = $this->createMock(CurrencyTransformer::class);
        $currencyInWords = 'someReturn eur';
        $currencyTransformer->method('toWords')->with(123, 'EUR')->willReturn($currencyInWords);

        $toWordsMock = $this->createMock(NumberToWords::class);
        $toWordsMock->expects($this->once())
            ->method('getCurrencyTransformer')
            ->with('en')
            ->willReturn($currencyTransformer);

        $languageStub = $this->createMock(LanguageInterface::class);
        $languageStub->method('getLanguageAbbreviation')->willReturn('en');

        $sut = new NumberWordingService($languageStub, $toWordsMock);
        $this->assertSame($currencyInWords, $sut->currencyToWords(123, 'EUR'));
    }
}
