<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Email\Service;

use FreshAdvance\Invoice\DataType\InvoiceDataInterface;

class InvoiceFilenameLengthFilter implements InvoiceFilenameCalculatorInterface
{
    public const MAX_LENGTH = 250;

    public function __construct(
        private InvoiceFilenameCalculatorInterface $invoiceFilenameCalculator,
    ) {
    }

    public function calculateByFormat(string $format, InvoiceDataInterface $invoiceData): string
    {
        $result = $this->invoiceFilenameCalculator->calculateByFormat($format, $invoiceData);

        $result = $this->limitLength($result);

        return $result;
    }

    private function limitLength(string $result): string
    {
        return substr($result, 0, self::MAX_LENGTH);
    }
}
