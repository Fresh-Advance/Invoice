<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Service;

use FreshAdvance\Invoice\DataType\PdfDataInterface;
use Mpdf\Mpdf;

class PdfGenerator
{
    public function __construct(
        protected Mpdf $pdfProcessor
    ) {
    }

    public function getBinaryPdfFromData(PdfDataInterface $pdfData): string
    {
        $this->pdfProcessor->SetHTMLHeader($pdfData->getHeader());
        $this->pdfProcessor->WriteHTML($pdfData->getContent());
        $this->pdfProcessor->SetHTMLFooter($pdfData->getFooter());

        return $this->pdfProcessor->OutputBinaryData();
    }
}
