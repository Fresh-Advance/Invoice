<?php

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
