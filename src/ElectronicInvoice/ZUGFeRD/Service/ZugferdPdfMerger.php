<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\ElectronicInvoice\ZUGFeRD\Service;

use FreshAdvance\Invoice\ElectronicInvoice\ZUGFeRD\Factory\ZugferdPdfMergerFactoryInterface;

class ZugferdPdfMerger implements ZugferdPdfMergerInterface
{
    public function __construct(
        private readonly ZugferdPdfMergerFactoryInterface $mergerFactory,
    ) {
    }

    public function mergeXmlToPdf(string $xml, string $pdfFilePath): void
    {
        $merger = $this->mergerFactory->createPdfMerger($xml, $pdfFilePath);
        $merger->generateDocument();
        $merger->saveDocument($pdfFilePath);
    }
}
