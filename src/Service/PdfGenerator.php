<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Service;

use FreshAdvance\Invoice\DataType\InvoiceDataInterface;
use FreshAdvance\Invoice\DataType\PdfData;
use Mpdf\Mpdf;
use OxidEsales\Eshop\Core\Language;
use OxidEsales\EshopCommunity\Internal\Framework\Templating\TemplateRendererInterface;
use Symfony\Component\Filesystem\Path;

class PdfGenerator
{
    protected const INVOICE_TEMPLATE = '@fa_invoice/invoice/body';

    public function __construct(
        protected Mpdf $pdfProcessor,
        protected TemplateRendererInterface $templateRenderer,
        protected Language $shopLanguage,
        protected ModuleSettings $moduleSettings
    ) {
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
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
        $pdfData = $this->preparePdfData($invoiceData);

        $this->pdfProcessor->SetHTMLHeader($pdfData->getHeader());
        $this->pdfProcessor->WriteHTML($pdfData->getContent());
        $this->pdfProcessor->SetHTMLFooter($pdfData->getFooter());
    }

    protected function preparePdfData(InvoiceDataInterface $invoiceData): PdfData
    {
        $currentLanguage = $this->shopLanguage->getTplLanguage();
        $this->shopLanguage->setTplLanguage((int)$invoiceData->getLanguageId());

        $html = $this->templateRenderer->renderTemplate(self::INVOICE_TEMPLATE, ['invoice' => $invoiceData]);

        $this->shopLanguage->setTplLanguage((int)$currentLanguage);

        return new PdfData(
            htmlContent: $html,
            htmlFooter: $this->moduleSettings->getDocumentFooter()
        );
    }
}
