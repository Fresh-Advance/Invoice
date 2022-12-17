<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Transition\Controller\Admin;

use FreshAdvance\Invoice\Service\Form;
use FreshAdvance\Invoice\Service\Invoice;
use FreshAdvance\Invoice\Service\PdfGenerator;
use FreshAdvance\Invoice\Traits\ServiceContainer;
use OxidEsales\Eshop\Application\Controller\Admin\AdminController;
use OxidEsales\Eshop\Core\Utils;

class InvoiceController extends AdminController
{
    use ServiceContainer;

    protected $_sThisTemplate = '@fa_invoice/admin/invoice';

    public function render()
    {
        $orderService = $this->getServiceFromContainer(Invoice::class);
        $this->addTplParam('invoiceData', $orderService->getInvoiceDataByOrderId($this->getEditObjectId()));

        return parent::render();
    }

    public function generate(): void
    {
        $invoiceService = $this->getServiceFromContainer(Invoice::class);

        $formService = $this->getServiceFromContainer(Form::class);
        $invoiceService->saveOrderInvoiceData($formService->getConfigurationFromRequest());

        $invoiceData = $invoiceService->getInvoiceDataByOrderId($this->getEditObjectId());

        $pdfGenerator = $this->getServiceFromContainer(PdfGenerator::class);
        $pdfGenerator->generate($invoiceData);
    }

    public function downloadOrderInvoice(): void
    {
        $invoiceService = $this->getServiceFromContainer(Invoice::class);
        $invoiceData = $invoiceService->getInvoiceDataByOrderId($this->getEditObjectId());

        $utils = $this->getServiceFromContainer(Utils::class);
        $utils->setHeader('Content-Type: application/pdf');
        $utils->setHeader('Content-Disposition:attachment;filename=' . $invoiceData->getInvoiceFilename());

        /** @var string $fileContent */
        $fileContent = file_get_contents($invoiceData->getInvoicePath()) ?: '';
        $utils->showMessageAndExit($fileContent);
    }
}
