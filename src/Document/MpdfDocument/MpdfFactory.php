<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Document\MpdfDocument;

use Mpdf\Mpdf;

class MpdfFactory
{
    public function __construct(
    ) {
    }

    public function create(): Mpdf
    {
        return new Mpdf();
    }
}
