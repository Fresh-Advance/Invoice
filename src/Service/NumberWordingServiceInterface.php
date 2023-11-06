<?php

namespace FreshAdvance\Invoice\Service;

interface NumberWordingServiceInterface
{
    public function currencyToWords(int $sumWithCents, string $currency): string;
}
