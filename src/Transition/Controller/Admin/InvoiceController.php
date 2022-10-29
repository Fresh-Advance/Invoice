<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Transition\Controller\Admin;

use FreshAdvance\Invoice\DataType\PdfData;
use FreshAdvance\Invoice\Service\Invoice;
use FreshAdvance\Invoice\Service\Order as OrderService;
use FreshAdvance\Invoice\Service\PdfGenerator;
use FreshAdvance\Invoice\Traits\ServiceContainer;
use OxidEsales\Eshop\Application\Controller\Admin\AdminController;
use OxidEsales\Eshop\Core\Registry;

class InvoiceController extends AdminController
{
    use ServiceContainer;

    protected $_sThisTemplate = '@fa_invoice/admin/invoice';

    public function render()
    {
        $orderService = $this->getServiceFromContainer(OrderService::class);

        $this->addTplParam('order', $orderService->getOrder($this->getEditObjectId()));

        return parent::render();
    }

    public function generate(): void
    {
        $invoiceService = $this->getServiceFromContainer(Invoice::class);
        $invoiceData = $invoiceService->getInvoiceDataByOrderId($this->getEditObjectId());

        $lang = Registry::getLang();
        $currentLanguage = $lang->getTplLanguage();
        $lang->setTplLanguage($invoiceData->getLanguageId());

        $pdfGenerator = $this->getServiceFromContainer(PdfGenerator::class);
        $pdfData = $pdfGenerator->preparePdfData($invoiceData);
        $pdfGenerator->generate($pdfData, '/var/www/invoices/example.pdf');

        $lang->setTplLanguage($currentLanguage);
    }
}
