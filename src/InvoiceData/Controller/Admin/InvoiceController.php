<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\InvoiceData\Controller\Admin;

use FreshAdvance\Invoice\InvoiceData\InvoiceConfiguration\Repository\InvoiceConfigurationRepositoryInterface;
use FreshAdvance\Invoice\InvoiceData\Service\Invoice;
use FreshAdvance\Invoice\InvoiceData\Service\InvoiceFileServiceInterface;
use FreshAdvance\Invoice\Pdf\InvoiceGeneratorInterface;
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
            $invoiceFileService = $this->getService(InvoiceFileServiceInterface::class);

            $this->addTplParam('invoiceExists', true);
            $this->addTplParam('invoiceFileName', $invoiceFileService->getInvoiceFileName($invoiceData));

            /** @var int $fileTimestamp */
            $fileTimestamp = filemtime($invoiceData->getInvoicePath());
            $this->addTplParam('invoiceDate', date('Y-m-d H:i:s', $fileTimestamp));
        }

        return parent::render();
    }

    public function saveData(): void
    {
        $invoiceService = $this->getService(Invoice::class);
        $invoiceConfigurationRepository = $this->getService(InvoiceConfigurationRepositoryInterface::class);
        $requestService = $this->getService(RequestInterface::class);
        $generator = $this->getService(InvoiceGeneratorInterface::class);

        $invoiceConfigurationRepository->save($requestService->getInvoiceConfigurationFromRequest());

        $invoiceData = $invoiceService->getInvoiceDataByOrderId($requestService->getInvoiceIdFromRequest());
        $generator->generate($invoiceData);
    }

    public function downloadOrderInvoice(): void
    {
        $request = $this->getService(RequestInterface::class);
        $invoiceDataService = $this->getService(Invoice::class);
        $invoiceFileService = $this->getService(InvoiceFileServiceInterface::class);

        $invoiceData = $invoiceDataService->getInvoiceDataByOrderId($request->getInvoiceIdFromRequest());

        $invoiceFileService->triggerInvoiceFileDownload(
            $invoiceFileService->getInvoiceFileName($invoiceData),
            $invoiceData->getInvoicePath()
        );
    }
}
