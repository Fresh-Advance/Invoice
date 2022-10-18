<?php

namespace FreshAdvance\Invoice\Service;

use FreshAdvance\Invoice\Exception\OrderNotFound;
use OxidEsales\Eshop\Application\Model\Order as OrderModel;

class Order
{
    public function getOrder(string $orderId): OrderModel
    {
        $order = oxNew(OrderModel::class);
        if (!$orderId || !$order->load($orderId)) {
            throw new OrderNotFound(sprintf('Order "%s" not found', $orderId));
        }
        return $order;
    }
}