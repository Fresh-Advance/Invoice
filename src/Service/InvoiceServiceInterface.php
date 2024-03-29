<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Service;

interface InvoiceServiceInterface
{
    public function triggerInvoiceFileDownload(string $fileName, string $filePath): void;
}
