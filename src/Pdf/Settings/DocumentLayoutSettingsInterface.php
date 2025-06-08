<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Pdf\Settings;

interface DocumentLayoutSettingsInterface
{
    public function getMarginTop(): string;
    public function getMarginBottom(): string;
    public function getMarginLeft(): string;
    public function getMarginRight(): string;

    public function getDocumentHeader(): string;
    public function getDocumentFooter(): string;
}
