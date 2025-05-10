<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Document\MpdfDocument;

use FreshAdvance\Invoice\Settings\ModuleSettingsInterface;
use Mpdf\Mpdf;

class MpdfFactory
{
    public function __construct(
        readonly private ModuleSettingsInterface $moduleSettings
    ) {
    }

    public function create(): Mpdf
    {
        return new Mpdf([
            'PDFA' => $this->moduleSettings->isForArchive()
        ]);
    }
}
