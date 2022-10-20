<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Transition\Controller\Admin;

use FreshAdvance\Invoice\Service\Order as OrderService;
use FreshAdvance\Invoice\Traits\ServiceContainer;
use OxidEsales\Eshop\Application\Controller\Admin\AdminController;

class InvoiceController extends AdminController
{
    use ServiceContainer;

    protected $_sThisTemplate = '@fa_invoice/admin/invoice';
    protected OrderService $orderService;

    public function render()
    {
        $orderService = $this->getServiceFromContainer(OrderService::class);

        $this->addTplParam('order', $orderService->getOrder($this->getEditObjectId()));

        return parent::render();
    }
}
