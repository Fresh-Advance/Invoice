<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

namespace FreshAdvance\Invoice\ElectronicInvoice\ZUGFeRD\Factory;

use horstoeko\zugferd\ZugferdDocumentPdfMerger;

class ZugferdPdfMergerFactory implements ZugferdPdfMergerFactoryInterface
{
    public function createPdfMerger(string $xml, string $pdfFilePath): ZugferdDocumentPdfMerger
    {
        return new ZugferdDocumentPdfMerger(
            xmlDataOrFilename: $xml,
            pdfData: $pdfFilePath,
        );
    }
}
