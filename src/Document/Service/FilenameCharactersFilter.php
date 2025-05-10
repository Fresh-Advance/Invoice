<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Document\Service;

use FreshAdvance\Invoice\DataType\InvoiceDataInterface;

class FilenameCharactersFilter implements FilenameCalculatorInterface
{
    public function __construct(
        readonly private FilenameCalculatorInterface $invoiceFilenameCalculator,
    ) {
    }

    public function calculateByFormat(string $format, InvoiceDataInterface $invoiceData): string
    {
        $result = $this->invoiceFilenameCalculator->calculateByFormat($format, $invoiceData);

        return (string)preg_replace(
            "/[^\p{L}\(\)\[\]\{\}!\@\#\$\%\^\&\_\-\+\=,\.\d]/ui",
            '-',
            $result
        );
    }
}
