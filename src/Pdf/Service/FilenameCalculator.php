<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Pdf\Service;

use FreshAdvance\Invoice\InvoiceData\DataType\InvoiceDataInterface;

class FilenameCalculator implements FilenameCalculatorInterface
{
    public function __construct(
        private readonly FormatCalculatorInterface $formatCalculator,
    ) {
    }

    public function calculateByFormat(string $format, InvoiceDataInterface $invoiceData): string
    {
        return $this->formatCalculator->calculateByFormat($format, $invoiceData);
    }
}
