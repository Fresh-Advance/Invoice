<?php

namespace FreshAdvance\Invoice\Repository;

use FreshAdvance\Invoice\Exception\ShopNotFound;
use OxidEsales\Eshop\Application\Model\Shop as ShopModel;

interface ShopRepositoryInterface
{
    /**
     * @throws ShopNotFound
     */
    public function getShop(int $shopId): ShopModel;
}
