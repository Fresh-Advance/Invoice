<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Service;

use FreshAdvance\Invoice\Exception\OrderNotFound;
use FreshAdvance\Invoice\Exception\RequestParameterMissing;
use OxidEsales\Eshop\Application\Model\Order as OrderModel;
use OxidEsales\Eshop\Core\Request;

class Order
{
    /**
     * @throws OrderNotFound
     */
    public function getOrder(string $orderId): OrderModel
    {
        $order = oxNew(OrderModel::class);
        if (!$orderId || !$order->load($orderId)) {
            throw new OrderNotFound(sprintf('Order "%s" not found', $orderId));
        }
        return $order;
    }
}
