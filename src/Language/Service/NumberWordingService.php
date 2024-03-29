<?php

namespace FreshAdvance\Invoice\Language\Service;

use NumberToWords\NumberToWords;

class NumberWordingService implements NumberWordingServiceInterface
{
    public function __construct(
        protected LanguageInterface $language,
        protected NumberToWords $toWordsService
    ) {
    }

    public function currencyToWords(int $sumWithCents, string $currency): string
    {
        $abbreviation = $this->language->getLanguageAbbreviation();
        $currencyTransformer = $this->toWordsService->getCurrencyTransformer($abbreviation);
        return $currencyTransformer->toWords($sumWithCents, $currency);
    }
}
