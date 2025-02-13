<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Tests\Unit\Document\Service;

use FreshAdvance\Invoice\DataType\InvoiceDataInterface;
use FreshAdvance\Invoice\Document\Service\FilenameCalculatorInterface;

class FilenameCharactersFilterTest extends \PHPUnit\Framework\TestCase
{
    /** @dataProvider filenamesProvider */
    public function testBadCharactersFilteredOut(string $expectedFilename, string $initialFilename): void
    {
        $calculatorStub = $this->createMock(FilenameCalculatorInterface::class);
        $calculatorStub->method('calculateByFormat')
            ->with(
                $format = uniqid(),
                $invoiceData = $this->createStub(InvoiceDataInterface::class)
            )
            ->willReturn($initialFilename);

        $sut = new \FreshAdvance\Invoice\Document\Service\FilenameCharactersFilter(
            invoiceFilenameCalculator: $calculatorStub
        );

        $this->assertSame($expectedFilename, $sut->calculateByFormat($format, $invoiceData));
    }

    public function filenamesProvider(): \Generator
    {
        yield 'Deutsch characters stay' => [
            'expectedFilename' => 'Straßenbahnkönnen',
            'initialFilename' => 'Straßenbahnkönnen',
        ];

        yield 'Russian characters stay' => [
            'expectedFilename' => 'абвгд',
            'initialFilename' => 'абвгд',
        ];

        yield 'Numbers stay' => [
            'expectedFilename' => '1234567890',
            'initialFilename' => '1234567890',
        ];

        yield 'Allowed characters stay' => [
            'expectedFilename' => '()[]{}!@#$%^&_-+=,.',
            'initialFilename' => '()[]{}!@#$%^&_-+=,.',
        ];

        yield 'Bad characters are replaced' => [
            'expectedFilename' => '---------',
            'initialFilename' => "/\\:*?\"<>|",
        ];

        yield 'More interesting format' => [
            'expectedFilename' => '[Straßenbahnkönnen]-12345.pdf',
            'initialFilename' => '[Straßenbahnkönnen]-12345.pdf',
        ];
    }
}
