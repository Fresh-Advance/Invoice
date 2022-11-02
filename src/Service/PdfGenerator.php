<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Service;

use FreshAdvance\Invoice\DataType\InvoiceData;
use FreshAdvance\Invoice\DataType\InvoiceDataInterface;
use FreshAdvance\Invoice\DataType\PdfData;
use FreshAdvance\Invoice\DataType\PdfDataInterface;
use Mpdf\Mpdf;
use OxidEsales\EshopCommunity\Internal\Framework\Templating\TemplateRendererInterface;
use Webmozart\PathUtil\Path;

class PdfGenerator
{
    protected const INVOICE_TEMPLATE = '@fa_invoice/invoice/body';

    public function __construct(
        protected Mpdf $pdfProcessor,
        protected TemplateRendererInterface $templateRenderer
    ) {
    }

    public function generate(PdfDataInterface $pdfData, string $filename): void
    {
        $this->configurePdfProcessor($pdfData);

        $directory = Path::getDirectory($filename);
        if (!is_dir($directory)) {
            mkdir(Path::getDirectory($filename), 0777, true);
        }

        $this->pdfProcessor->OutputFile($filename);
    }

    private function configurePdfProcessor($pdfData): void
    {
        $this->pdfProcessor->SetHTMLHeader($pdfData->getHeader());
        $this->pdfProcessor->WriteHTML($pdfData->getContent());
        $this->pdfProcessor->SetHTMLFooter($pdfData->getFooter());
    }

    public function preparePdfData(InvoiceDataInterface $invoiceData): PdfData
    {
        $html = $this->templateRenderer->renderTemplate(self::INVOICE_TEMPLATE, ['invoice' => $invoiceData]);
        return new PdfData(htmlContent: $html);
    }
}
