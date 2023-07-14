<?php

namespace FreshAdvance\Invoice\Document;

use FreshAdvance\Invoice\DataType\InvoiceDataInterface;

interface InvoiceGeneratorInterface
{
    public function generate(InvoiceDataInterface $invoiceData): void;
}
