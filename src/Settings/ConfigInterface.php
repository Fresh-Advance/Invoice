<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Settings;

interface ConfigInterface
{
    public function getShopDefaultLanguageId(int $shopId): int;
}
