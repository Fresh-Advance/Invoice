<?php

namespace FreshAdvance\Invoice\DataType;

use OxidEsales\Eshop\Application\Model\Order;
use OxidEsales\Eshop\Application\Model\Shop;

interface InvoiceDataInterface
{
    public function getOrder(): Order;

    public function getShop(): Shop;
}