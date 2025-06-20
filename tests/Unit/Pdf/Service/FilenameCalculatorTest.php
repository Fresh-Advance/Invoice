<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Tests\Unit\Pdf\Service;

use FreshAdvance\Invoice\DataType\InvoiceDataInterface;
use FreshAdvance\Invoice\Pdf\Service\FilenameCalculator;
use FreshAdvance\Invoice\Pdf\Service\FormatCalculatorInterface;

class FilenameCalculatorTest extends \PHPUnit\Framework\TestCase
{
    public function testBasicCalculatorReturnsFormatCalculationResult(): void
    {
        $formatStub = uniqid();
        $invoiceConfigurationStub = $this->createStub(InvoiceDataInterface::class);

        $formatCalculatorMock = $this->createMock(FormatCalculatorInterface::class);
        $formatCalculatorMock->expects($this->once())
            ->method('calculateByFormat')
            ->with($formatStub, $invoiceConfigurationStub)
            ->willReturn($formattedResult = uniqid());

        $sut = new FilenameCalculator(
            formatCalculator: $formatCalculatorMock,
        );

        $result = $sut->calculateByFormat($formatStub, $invoiceConfigurationStub);
        $this->assertSame($formattedResult, $result);
    }
}
