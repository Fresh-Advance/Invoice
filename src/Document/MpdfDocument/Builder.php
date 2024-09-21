<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Document\MpdfDocument;

use FreshAdvance\Invoice\DataType\InvoiceDataInterface;
use FreshAdvance\Invoice\Document\InvoiceGeneratorInterface;
use FreshAdvance\Invoice\Language\Service\LanguageInterface;
use FreshAdvance\Invoice\Language\Service\NumberWordingServiceInterface;
use FreshAdvance\Invoice\Service\OrderServiceInterface;
use FreshAdvance\Invoice\Settings\Service\DocumentLayoutSettingsServiceInterface;
use Mpdf\Mpdf;
use OxidEsales\EshopCommunity\Internal\Framework\Templating\TemplateRendererInterface;
use Symfony\Component\Filesystem\Path;

// todo: the builder became too big. Split the builder part and Invoice generator part.
class Builder implements InvoiceGeneratorInterface
{
    public const INVOICE_TEMPLATE = '@fa_invoice/invoice/body';

    public function __construct(
        protected Mpdf $pdfProcessor,
        protected TemplateRendererInterface $templateRenderer,
        protected LanguageInterface $shopLanguage,
        protected DocumentLayoutSettingsServiceInterface $layoutSettingsService,
        protected NumberWordingServiceInterface $numberWordingService,
        protected OrderServiceInterface $orderService,
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

        $this->orderService->prepareOrderInvoiceNumber($invoiceData);
        $this->pdfProcessor->OutputFile($invoiceFilePath);
    }

    private function configurePdfProcessor(InvoiceDataInterface $invoiceData): void
    {
        $htmlContent = $this->preparePdfData($invoiceData);
        $this->pdfProcessor->WriteHTML($htmlContent);
    }

    protected function preparePdfData(InvoiceDataInterface $invoiceData): string
    {
        $currentLanguage = $this->shopLanguage->getTplLanguage();
        try {
            $this->shopLanguage->forceSetTplLanguage((int)$invoiceData->getLanguageId());
            $html = $this->templateRenderer->renderTemplate(
                self::INVOICE_TEMPLATE,
                [
                    'invoice' => $invoiceData,
                    'wording' => $this->numberWordingService,
                    'layoutSettings' => $this->layoutSettingsService,
                ]
            );
        } finally {
            $this->shopLanguage->forceSetTplLanguage((int)$currentLanguage);
        }

        return $html;
    }
}
