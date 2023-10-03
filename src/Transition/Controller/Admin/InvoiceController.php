<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Transition\Controller\Admin;

use FreshAdvance\Invoice\DataType\InvoiceDataInterface;
use FreshAdvance\Invoice\Document\InvoiceGeneratorInterface;
use FreshAdvance\Invoice\Service\Invoice;
use FreshAdvance\Invoice\Service\InvoiceServiceInterface;
use FreshAdvance\Invoice\Service\RequestDataConverterInterface;
use FreshAdvance\Invoice\Traits\ServiceContainer;
use FreshAdvance\Invoice\Transition\Core\RequestInterface;
use OxidEsales\Eshop\Application\Controller\Admin\AdminController;

class InvoiceController extends AdminController
{
    use ServiceContainer;

    public const ORDER_ID_REQUEST_PARAM = 'orderId';

    protected $_sThisTemplate = '@fa_invoice/admin/invoice';

    public function render()
    {
        $orderService = $this->getServiceFromContainer(Invoice::class);
        $this->addTplParam('invoiceData', $orderService->getInvoiceDataByOrderId($this->getEditObjectId()));

        return parent::render();
    }

    public function saveData(): void
    {
        $invoiceService = $this->getServiceFromContainer(Invoice::class);
        $requestService = $this->getServiceFromContainer(RequestDataConverterInterface::class);
        $invoiceService->saveOrderInvoiceData($requestService->getConfigurationFromRequest());
    }

    public function downloadOrderInvoice(): void
    {
        $orderId = $this->getOrderIdFromRequest();

        $invoiceDataService = $this->getServiceFromContainer(Invoice::class);
        $invoiceData = $invoiceDataService->getInvoiceDataByOrderId($orderId);

        $this->proceedToGenerateAndDownload($invoiceData, $invoiceDataService);
    }

    protected function getOrderIdFromRequest(): string
    {
        $request = $this->getServiceFromContainer(RequestInterface::class);
        /** @var string|null $value */
        $value = $request->getRequestEscapedParameter(self::ORDER_ID_REQUEST_PARAM);
        return (string)$value;
    }

    protected function proceedToGenerateAndDownload(
        InvoiceDataInterface $invoiceData,
        Invoice $invoiceDataService
    ): void {
        $generator = $this->getServiceFromContainer(InvoiceGeneratorInterface::class);
        $generator->generate($invoiceData);

        $invoiceService = $this->getServiceFromContainer(InvoiceServiceInterface::class);
        $invoiceService->triggerInvoiceFileDownload(
            $invoiceDataService->getInvoiceFileName($invoiceData->getInvoiceConfiguration()),
            $invoiceData->getInvoicePath()
        );
    }
}
