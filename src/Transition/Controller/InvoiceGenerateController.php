<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Transition\Controller;

use FreshAdvance\Invoice\Service\Order;
use FreshAdvance\Invoice\Traits\ServiceContainer;
use OxidEsales\Eshop\Application\Controller\FrontendController;
use OxidEsales\EshopCommunity\Core\Registry;

class InvoiceGenerateController extends FrontendController
{
    use ServiceContainer;

    public function generate(): void
    {
        $orderService = $this->getServiceFromContainer(Order::class);
        Registry::getUtils()->showMessageAndExit($orderService->getRequestOrder()->getId());
    }
}
