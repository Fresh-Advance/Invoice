<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\ElectronicInvoice\ZUGFeRD;

use FreshAdvance\Invoice\DataType\InvoiceDataInterface;
use FreshAdvance\Invoice\ElectronicInvoice\ZUGFeRD\Service\ZugferdPdfMergerInterface;
use FreshAdvance\Invoice\ElectronicInvoice\ZUGFeRD\Service\ZugferdXmlBuilderInterface;
use FreshAdvance\Invoice\Pdf\InvoiceGeneratorInterface;

class InvoiceGeneratorDecorator implements InvoiceGeneratorInterface
{
    public function __construct(
        private readonly InvoiceGeneratorInterface $originalInvoiceGenerator,
        private readonly ZugferdXmlBuilderInterface $zugferdXmlBuilder,
        private readonly ZugferdPdfMergerInterface $zugferdPdfMerger,
    ) {
    }

    public function generate(InvoiceDataInterface $invoiceData): string
    {
        $pdfPath = $this->originalInvoiceGenerator->generate($invoiceData);

        $xml = $this->zugferdXmlBuilder->getXml($invoiceData);
        $this->zugferdPdfMerger->mergeXmlToPdf($xml, $pdfPath);

        return $pdfPath;
    }
}
