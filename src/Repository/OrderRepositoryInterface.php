<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Repository;

use FreshAdvance\Invoice\Exception\OrderNotFound;
use OxidEsales\Eshop\Application\Model\Order as OrderModel;

interface OrderRepositoryInterface
{
    /**
     * @throws OrderNotFound
     */
    public function getByOrderId(string $orderId): OrderModel;
}
