<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Email\Service;

use FreshAdvance\Invoice\DataType\InvoiceDataInterface;

interface InvoiceFilenameCalculatorInterface
{
    public function calculateByFormat(string $format, InvoiceDataInterface $invoiceData): string;
}
