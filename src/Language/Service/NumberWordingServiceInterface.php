<?php

namespace FreshAdvance\Invoice\Language\Service;

interface NumberWordingServiceInterface
{
    public function currencyToWords(int $sumWithCents, string $currency): string;
}
