<?php

namespace FreshAdvance\Invoice\DataType;

interface PdfDataInterface
{
    public function getContent(): string;

    public function getHeader(): ?string;

    public function getFooter(): ?string;
}