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
use FreshAdvance\Invoice\Transition\Core\UtilsInterface;
use OxidEsales\Eshop\Application\Controller\Admin\AdminController;

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

    public function saveData(): void
    {
        $invoiceService = $this->getServiceFromContainer(Invoice::class);
        $requestService = $this->getServiceFromContainer(RequestDataConverterInterface::class);
        $invoiceService->saveOrderInvoiceData($requestService->getConfigurationFromRequest());
    }
}
