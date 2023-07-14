<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Transition\Controller\Admin;

use FreshAdvance\Invoice\Document\InvoiceGeneratorInterface;
use FreshAdvance\Invoice\Service\Invoice;
use FreshAdvance\Invoice\Service\RequestDataConverterInterface;
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

        $requestService = $this->getServiceFromContainer(RequestDataConverterInterface::class);
        $invoiceService->saveOrderInvoiceData($requestService->getConfigurationFromRequest());

        $invoiceData = $invoiceService->getInvoiceDataByOrderId($this->getEditObjectId());

        $invoiceGenerator = $this->getServiceFromContainer(InvoiceGeneratorInterface::class);
        $invoiceGenerator->generate($invoiceData);
    }

    public function downloadOrderInvoice(): void
    {
        $invoiceService = $this->getServiceFromContainer(Invoice::class);
        $invoiceData = $invoiceService->getInvoiceDataByOrderId($this->getEditObjectId());

        $fileName = $invoiceService->getInvoiceFileName($invoiceData->getInvoiceConfiguration());

        /** @var Utils $utils */
        $utils = $this->getServiceFromContainer('FreshAdvance\Invoice\Core\Utils');
        $utils->setHeader('Content-Type: application/pdf');
        $utils->setHeader('Content-Disposition:attachment;filename=' . $fileName);

        /** @var string $fileContent */
        $fileContent = file_get_contents($invoiceData->getInvoicePath()) ?: '';
        $utils->showMessageAndExit($fileContent);
    }
}
