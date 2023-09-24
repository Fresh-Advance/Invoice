<?php

namespace FreshAdvance\Invoice\Transition\Core;

interface ConfigInterface
{
    public function getShopDefaultLanguageId(int $shopId): int;
}
