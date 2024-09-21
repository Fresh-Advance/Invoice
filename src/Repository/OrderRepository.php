<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Repository;

use FreshAdvance\Invoice\Exception\OrderNotFound;
use OxidEsales\Eshop\Application\Model\Order as OrderModel;

class OrderRepository implements OrderRepositoryInterface
{
    /**
     * @throws OrderNotFound
     */
    public function getByOrderId(string $orderId): OrderModel
    {
        $order = oxNew(OrderModel::class);
        if (!$orderId || !$order->load($orderId)) {
            throw new OrderNotFound(sprintf('Order "%s" not found', $orderId));
        }
        return $order;
    }

    public function fillEmptyInvoiceNumber(OrderModel $orderModel): void
    {
        if (!$orderModel->getFieldData('oxbillnr')) {
            $orderModel->assign(
                ['oxbillnr' => $orderModel->getNextBillNum()]
            );
            $orderModel->save();
        }
    }
}
