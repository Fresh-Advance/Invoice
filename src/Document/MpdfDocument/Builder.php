<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Document\MpdfDocument;

use FreshAdvance\Invoice\DataType\InvoiceDataInterface;
use FreshAdvance\Invoice\Document\InvoiceGeneratorInterface;
use FreshAdvance\Invoice\Document\Service\DocumentRendererInterface;
use Mpdf\Mpdf;
use Symfony\Component\Filesystem\Path;

class Builder implements InvoiceGeneratorInterface
{
    public function __construct(
        protected Mpdf $pdfProcessor,
        protected DocumentRendererInterface $documentRenderer,
    ) {
    }

    public function generate(InvoiceDataInterface $invoiceData): void
    {
        $this->configurePdfProcessor($invoiceData);

        $invoiceFilePath = $invoiceData->getInvoicePath();
        $directory = Path::getDirectory($invoiceFilePath);
        if (!is_dir($directory)) {
            mkdir(Path::getDirectory($invoiceFilePath), 0777, true);
        }

        $this->pdfProcessor->OutputFile($invoiceFilePath);
    }

    private function configurePdfProcessor(InvoiceDataInterface $invoiceData): void
    {
        $htmlContent = $this->documentRenderer->render($invoiceData);
        $this->pdfProcessor->WriteHTML($htmlContent);
    }
}
