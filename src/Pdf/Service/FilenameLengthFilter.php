<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Pdf\Service;

use FreshAdvance\Invoice\InvoiceData\DataType\InvoiceDataInterface;

use function substr;

class FilenameLengthFilter implements FilenameCalculatorInterface
{
    public const MAX_LENGTH = 250;

    public function __construct(
        readonly private FilenameCalculatorInterface $invoiceFilenameCalculator,
    ) {
    }

    public function calculateByFormat(string $format, InvoiceDataInterface $invoiceData): string
    {
        $result = $this->invoiceFilenameCalculator->calculateByFormat($format, $invoiceData);

        return substr($result, 0, self::MAX_LENGTH);
    }
}
