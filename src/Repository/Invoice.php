<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Repository;

use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;

class Invoice
{
    public function __construct(
        protected QueryBuilderFactoryInterface $queryBuilderFactory
    ) {
    }

    public function create(string $orderId): string
    {
        $queryBuilder = $this->queryBuilderFactory->create();
        $queryBuilder->insert('fa_invoices')
            ->values([
                'order_id' => ':order_id',
                'invoice_id' => ':invoice_id'
            ])
            ->setParameters([
                ':order_id' => $orderId,
                ':invoice_id' => 'someinvoiceid'
            ]);
        $queryBuilder->execute();

        return 'someinvoiceid';
    }
}
