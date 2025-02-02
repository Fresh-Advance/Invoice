<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Tests\Unit\Email\Service;

use FreshAdvance\Invoice\DataType\InvoiceDataInterface;
use FreshAdvance\Invoice\Email\Service\InvoiceFilenameCalculatorInterface;
use FreshAdvance\Invoice\Email\Service\InvoiceFilenameLengthFilter;

class InvoiceFilenameLengthFilterTest extends \PHPUnit\Framework\TestCase
{
    public function testLengthFiltered(): void
    {
        $filenameStub = str_repeat("x", 1000);

        $calculatorStub = $this->createMock(InvoiceFilenameCalculatorInterface::class);
        $calculatorStub->method('calculateByFormat')
            ->with(
                $format = uniqid(),
                $invoiceData = $this->createStub(InvoiceDataInterface::class)
            )
            ->willReturn($filenameStub);

        $sut = new InvoiceFilenameLengthFilter(
            invoiceFilenameCalculator: $calculatorStub
        );

        $result = $sut->calculateByFormat($format, $invoiceData);
        $this->assertSame($sut::MAX_LENGTH, strlen($result));
    }
}
