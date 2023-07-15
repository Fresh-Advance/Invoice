<?php

namespace FreshAdvance\Invoice\Repository;

use FreshAdvance\Invoice\DataType\InvoiceConfigurationInterface;

interface InvoiceConfigurationRepositoryInterface
{
    public function getOrderInvoice(string $orderId): ?InvoiceConfigurationInterface;

    public function saveInvoiceConfiguration(InvoiceConfigurationInterface $invoiceConfiguration): void;
}
