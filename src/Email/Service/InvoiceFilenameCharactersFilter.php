<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Email\Service;

use FreshAdvance\Invoice\DataType\InvoiceDataInterface;

class InvoiceFilenameCharactersFilter implements InvoiceFilenameCalculatorInterface
{
    public function __construct(
        private InvoiceFilenameCalculatorInterface $invoiceFilenameCalculator,
    ) {
    }

    public function calculateByFormat(string $format, InvoiceDataInterface $invoiceData): string
    {
        $result = $this->invoiceFilenameCalculator->calculateByFormat($format, $invoiceData);

        return preg_replace(
            "/[^\p{L}\(\)\[\]\{\}!\@\#\$\%\^\&\_\-\+\=,\.\d]/ui",
            '-',
            $result
        );
    }
}
