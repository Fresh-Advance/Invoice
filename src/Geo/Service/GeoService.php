<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Geo\Service;

use FreshAdvance\Invoice\Geo\Factory\CountryModelFactoryInterface;

class GeoService implements GeoServiceInterface
{
    public function __construct(
        private readonly CountryModelFactoryInterface $countryModelFactory,
    ) {
    }

    public function getCountryCodeById(string $countryId): ?string
    {
        try {
            $country = $this->countryModelFactory->createModelObject();
            $country->load($countryId);
        } catch (\Exception) {
            return null;
        }

        return (string)$country->getFieldData('OXISOALPHA2');
    }
}
