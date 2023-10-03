<?php

namespace FreshAdvance\Invoice\Settings;

interface ConfigInterface
{
    public function getShopDefaultLanguageId(int $shopId): int;
}
