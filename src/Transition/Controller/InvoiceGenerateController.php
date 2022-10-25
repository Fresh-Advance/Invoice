<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Transition\Controller;

use FreshAdvance\Invoice\DataType\PdfData;
use FreshAdvance\Invoice\Service\Invoice;
use FreshAdvance\Invoice\Service\PdfGenerator;
use FreshAdvance\Invoice\Traits\ServiceContainer;
use OxidEsales\Eshop\Application\Controller\FrontendController;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\EshopCommunity\Internal\Framework\Templating\TemplateRendererInterface;

class InvoiceGenerateController extends FrontendController
{
    use ServiceContainer;

    protected const INVOICE_TEMPLATE = '@fa_invoice/invoice/body';

    public function generate(): void
    {
        $invoiceService = $this->getServiceFromContainer(Invoice::class);
        $invoiceData = $invoiceService->getInvoiceData();

        Registry::getLang()->setTplLanguage($_GET['lang']);

        $templateRenderer = $this->getServiceFromContainer(TemplateRendererInterface::class);
        $html = $templateRenderer->renderTemplate(self::INVOICE_TEMPLATE, ['invoice' => $invoiceData]);

        $pdfGenerator = $this->getServiceFromContainer(PdfGenerator::class);
        header('Content-Type: application/pdf');
        echo $pdfGenerator->getBinaryPdfFromData(new PdfData(htmlContent: $html));
    }
}
