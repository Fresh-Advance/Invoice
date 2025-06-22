<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Tests\Unit\Pdf\Service;

use FreshAdvance\Invoice\InvoiceData\DataType\InvoiceDataInterface;
use FreshAdvance\Invoice\Pdf\Service\FilenameCalculatorInterface;
use FreshAdvance\Invoice\Pdf\Service\FilenameLengthFilter;

class FilenameLengthFilterTest extends \PHPUnit\Framework\TestCase
{
    public function testLengthFiltered(): void
    {
        $filenameStub = str_repeat("x", 1000);

        $calculatorStub = $this->createMock(FilenameCalculatorInterface::class);
        $calculatorStub->method('calculateByFormat')
            ->with(
                $format = uniqid(),
                $invoiceData = $this->createStub(InvoiceDataInterface::class)
            )
            ->willReturn($filenameStub);

        $sut = new FilenameLengthFilter(
            invoiceFilenameCalculator: $calculatorStub
        );

        $result = $sut->calculateByFormat($format, $invoiceData);
        $this->assertSame($sut::MAX_LENGTH, strlen($result));
    }
}
