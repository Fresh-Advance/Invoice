<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Transition\Controller;

use FreshAdvance\Invoice\Traits\ServiceContainer;
use OxidEsales\Eshop\Application\Controller\FrontendController;

class InvoiceController extends FrontendController
{
    use ServiceContainer;

    public function render()
    {
        die('invoice generation and download placeholder');
    }
}
