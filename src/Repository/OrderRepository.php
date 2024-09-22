<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Repository;

use Doctrine\DBAL\ForwardCompatibility\Result;
use FreshAdvance\Invoice\Exception\OrderNotFound;
use OxidEsales\Eshop\Application\Model\Order as OrderModel;
use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;

class OrderRepository implements OrderRepositoryInterface
{
    public function __construct(
        protected QueryBuilderFactoryInterface $queryBuilderFactory
    ) {
    }

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

    public function getInvoiceNumberByOrderId(string $orderId): string
    {
        $queryBuilder = $this->queryBuilderFactory->create();

        $queryBuilder->select('oxbillnr')
            ->from('oxorder')
            ->where('oxid = :orderId')
            ->setParameter('orderId', $orderId)
            ->setMaxResults(1);

        /** @var Result $result */
        $result = $queryBuilder->execute();

        /** @var false|string|int|null $invoiceNumber */
        $invoiceNumber = $result->fetchOne();

        if ($invoiceNumber === false) {
            throw new OrderNotFound(sprintf('Order "%s" not found', $orderId));
        }

        return (string)$invoiceNumber;
    }
}
