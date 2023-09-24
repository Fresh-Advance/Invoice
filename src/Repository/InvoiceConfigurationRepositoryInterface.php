<?php

namespace FreshAdvance\Invoice\Repository;

use FreshAdvance\Invoice\DataType\InvoiceConfigurationInterface;

interface InvoiceConfigurationRepositoryInterface
{
    public function getByOrderId(string $orderId): ?InvoiceConfigurationInterface;

    public function save(InvoiceConfigurationInterface $invoiceConfiguration): void;
}
