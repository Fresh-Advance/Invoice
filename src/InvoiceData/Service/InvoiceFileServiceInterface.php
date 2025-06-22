<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\InvoiceData\Service;

use FreshAdvance\Invoice\InvoiceData\DataType\InvoiceDataInterface;

interface InvoiceFileServiceInterface
{
    public function triggerInvoiceFileDownload(string $fileName, string $filePath): void;

    public function getInvoiceFileName(InvoiceDataInterface $invoiceData): string;
}
