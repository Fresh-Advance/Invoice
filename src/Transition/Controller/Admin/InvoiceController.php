<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Transition\Controller\Admin;

use FreshAdvance\Invoice\InvoiceData\Service\InvoiceServiceInterface;
use FreshAdvance\Invoice\Pdf\InvoiceGeneratorInterface;
use FreshAdvance\Invoice\Service\Invoice;
use FreshAdvance\Invoice\Settings\ModuleSettingsInterface;
use FreshAdvance\Invoice\Traits\ServiceContainer;
use FreshAdvance\Invoice\Transput\RequestInterface;
use OxidEsales\Eshop\Application\Controller\Admin\AdminController;

class InvoiceController extends AdminController
{
    use ServiceContainer;

    protected $_sThisTemplate = '@fa_invoice/admin/invoice';

    public function render()
    {
        $invoiceDataService = $this->getServiceFromContainer(Invoice::class);
        $invoiceData = $invoiceDataService->getInvoiceDataByOrderId($this->getEditObjectId());
        $this->addTplParam('invoiceData', $invoiceData);

        $moduleSettingsService = $this->getServiceFromContainer(ModuleSettingsInterface::class);
        $this->addTplParam('moduleSettings', $moduleSettingsService);

        if (is_file($invoiceData->getInvoicePath())) {
            $this->addTplParam('invoiceExists', true);
            $this->addTplParam('invoiceFileName', $invoiceDataService->getInvoiceFileName($invoiceData));

            /** @var int $fileTimestamp */
            $fileTimestamp = filemtime($invoiceData->getInvoicePath());
            $this->addTplParam('invoiceDate', date('Y-m-d H:i:s', $fileTimestamp));
        }

        return parent::render();
    }

    public function saveData(): void
    {
        $invoiceService = $this->getServiceFromContainer(Invoice::class);
        $requestService = $this->getServiceFromContainer(RequestInterface::class);
        $generator = $this->getServiceFromContainer(InvoiceGeneratorInterface::class);

        $invoiceService->saveOrderInvoiceData($requestService->getInvoiceConfigurationFromRequest());

        $invoiceData = $invoiceService->getInvoiceDataByOrderId($requestService->getInvoiceIdFromRequest());
        $generator->generate($invoiceData);
    }

    public function downloadOrderInvoice(): void
    {
        $request = $this->getServiceFromContainer(RequestInterface::class);
        $invoiceDataService = $this->getServiceFromContainer(Invoice::class);
        $invoiceService = $this->getServiceFromContainer(InvoiceServiceInterface::class);

        $invoiceData = $invoiceDataService->getInvoiceDataByOrderId($request->getInvoiceIdFromRequest());

        $invoiceService->triggerInvoiceFileDownload(
            $invoiceDataService->getInvoiceFileName($invoiceData),
            $invoiceData->getInvoicePath()
        );
    }
}
