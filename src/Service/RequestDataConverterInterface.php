<?php

namespace FreshAdvance\Invoice\Service;

use FreshAdvance\Invoice\DataType\InvoiceConfigurationInterface;

interface RequestDataConverterInterface
{
    public function getConfigurationFromRequest(): InvoiceConfigurationInterface;
}
