<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Pdf;

use FreshAdvance\Invoice\InvoiceData\DataType\InvoiceDataInterface;

interface InvoiceGeneratorInterface
{
    public function generate(InvoiceDataInterface $invoiceData): string;
}
