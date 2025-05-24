<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

namespace FreshAdvance\Invoice\Geo\Service;

interface GeoServiceInterface
{
    public function getCountryCodeById(string $countryId): ?string;
}
