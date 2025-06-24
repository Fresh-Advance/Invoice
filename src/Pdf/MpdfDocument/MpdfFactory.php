<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Pdf\MpdfDocument;

use FreshAdvance\Invoice\InvoiceData\Settings\FormatSettingsInterface;
use Mpdf\Mpdf;

class MpdfFactory
{
    public function __construct(
        readonly private FormatSettingsInterface $moduleSettings
    ) {
    }

    public function create(): Mpdf
    {
        return new Mpdf([
            'PDFA' => $this->moduleSettings->isForArchive()
        ]);
    }
}
