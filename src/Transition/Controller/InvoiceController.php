<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Transition\Controller;

use FreshAdvance\Invoice\DataType\InvoiceDataInterface;
use FreshAdvance\Invoice\Document\InvoiceGeneratorInterface;
use FreshAdvance\Invoice\Exception\AccessDenied;
use FreshAdvance\Invoice\Service\Invoice;
use FreshAdvance\Invoice\Service\InvoiceServiceInterface;
use FreshAdvance\Invoice\Traits\ServiceContainer;
use FreshAdvance\Invoice\Transition\Core\RequestInterface;
use OxidEsales\Eshop\Application\Controller\FrontendController;
use OxidEsales\Eshop\Application\Model\User;

class InvoiceController extends FrontendController
{
    use ServiceContainer;

    public const ORDER_ID_REQUEST_PARAM = 'orderId';

    /**
     * @throws AccessDenied if user have no rights
     */
    public function downloadOrderInvoice(): void
    {
        $orderId = $this->getOrderIdFromRequest();

        $invoiceDataService = $this->getServiceFromContainer(Invoice::class);
        $invoiceData = $invoiceDataService->getInvoiceDataByOrderId($orderId);

        $this->checkActiveUserAccessRights($invoiceData);

        $this->proceedToGenerateAndDownload($invoiceData, $invoiceDataService);
    }

    protected function getOrderIdFromRequest(): string
    {
        $request = $this->getServiceFromContainer(RequestInterface::class);
        /** @var string|null $value */
        $value = $request->getRequestEscapedParameter(self::ORDER_ID_REQUEST_PARAM);
        return (string)$value;
    }

    /**
     * @throws AccessDenied
     */
    protected function checkActiveUserAccessRights(InvoiceDataInterface $invoiceData): void
    {
        if ($this->isAdmin()) {
            return;
        }

        /** @var User|false $activeUser */
        $activeUser = $this->getUser();
        if (!$activeUser || $activeUser->getId() != $invoiceData->getOrder()->getOrderUser()->getId()) {
            throw new AccessDenied("Please login to use this function");
        }
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
