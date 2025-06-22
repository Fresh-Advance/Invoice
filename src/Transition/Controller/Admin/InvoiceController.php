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
use FreshAdvance\Invoice\Transput\RequestInterface;
use OxidEsales\Eshop\Application\Controller\Admin\AdminController;

class InvoiceController extends AdminController
{
    protected $_sThisTemplate = '@fa_invoice/admin/invoice';

    public function render()
    {
        $invoiceDataService = $this->getService(Invoice::class);
        $invoiceData = $invoiceDataService->getInvoiceDataByOrderId($this->getEditObjectId());
        $this->addTplParam('invoiceData', $invoiceData);

        $moduleSettingsService = $this->getService(ModuleSettingsInterface::class);
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
        $invoiceService = $this->getService(Invoice::class);
        $requestService = $this->getService(RequestInterface::class);
        $generator = $this->getService(InvoiceGeneratorInterface::class);

        $invoiceService->saveOrderInvoiceData($requestService->getInvoiceConfigurationFromRequest());

        $invoiceData = $invoiceService->getInvoiceDataByOrderId($requestService->getInvoiceIdFromRequest());
        $generator->generate($invoiceData);
    }

    public function downloadOrderInvoice(): void
    {
        $request = $this->getService(RequestInterface::class);
        $invoiceDataService = $this->getService(Invoice::class);
        $invoiceService = $this->getService(InvoiceServiceInterface::class);

        $invoiceData = $invoiceDataService->getInvoiceDataByOrderId($request->getInvoiceIdFromRequest());

        $invoiceService->triggerInvoiceFileDownload(
            $invoiceDataService->getInvoiceFileName($invoiceData),
            $invoiceData->getInvoicePath()
        );
    }
}
