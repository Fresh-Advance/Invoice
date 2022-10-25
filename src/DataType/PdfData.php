<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\DataType;

class PdfData implements PdfDataInterface
{
    public function __construct(
        protected string $htmlContent,
        protected ?string $htmlHeader = null,
        protected ?string $htmlFooter = null
    ) {
    }

    public function getContent(): string
    {
        return $this->htmlContent;
    }

    public function getHeader(): ?string
    {
        return $this->htmlHeader;
    }

    public function getFooter(): ?string
    {
        return $this->htmlFooter;
    }
}
