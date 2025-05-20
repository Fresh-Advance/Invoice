<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

namespace FreshAdvance\Invoice\ElectronicInvoice\ZUGFeRD\Service;

use FreshAdvance\Invoice\DataType\InvoiceDataInterface;

interface ZugferdXmlBuilderInterface
{
    public function getXml(InvoiceDataInterface $invoiceData): string;
}
