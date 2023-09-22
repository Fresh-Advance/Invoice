<?php

namespace FreshAdvance\Invoice\Service;

interface InvoiceServiceInterface
{
    public function triggerInvoiceFileDownload(string $fileName, string $filePath): void;
}
