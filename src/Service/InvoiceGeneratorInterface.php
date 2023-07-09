<?php

namespace FreshAdvance\Invoice\Service;

use FreshAdvance\Invoice\DataType\InvoiceDataInterface;

interface InvoiceGeneratorInterface
{
    public function generate(InvoiceDataInterface $invoiceData): void;
}
