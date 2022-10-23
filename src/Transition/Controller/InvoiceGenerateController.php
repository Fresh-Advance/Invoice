<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Transition\Controller;

use FreshAdvance\Invoice\Service\Invoice;
use FreshAdvance\Invoice\Traits\ServiceContainer;
use Mpdf\Mpdf;
use OxidEsales\Eshop\Application\Controller\FrontendController;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\EshopCommunity\Internal\Framework\Templating\TemplateRendererInterface;

class InvoiceGenerateController extends FrontendController
{
    use ServiceContainer;

    protected const INVOICE_TEMPLATE = '@fa_invoice/invoice/body';

    public function generate(): void
    {
        Registry::getLang()->setTplLanguage($_GET['lang']);

        $invoiceService = $this->getServiceFromContainer(Invoice::class);

        $templateRenderer = $this->getServiceFromContainer(TemplateRendererInterface::class);
        $pdfGenerator = $this->getServiceFromContainer(Mpdf::class);
        $pdfGenerator->WriteHTML($templateRenderer->renderTemplate(self::INVOICE_TEMPLATE, [
            'invoice' => $invoiceService->getInvoiceData()
        ]));
        $pdfGenerator->Output();
    }
}
