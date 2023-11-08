<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Settings;

use OxidEsales\Eshop\Core\Config;

class ConfigProxy implements ConfigInterface
{
    public function __construct(
        private Config $shopConfig
    ) {
    }

    public function getShopDefaultLanguageId(int $shopId): int
    {
        /** @var string|int $value */
        $value = $this->shopConfig->getShopConfVar('sDefaultLang', $shopId);
        return (int)$value;
    }
}
