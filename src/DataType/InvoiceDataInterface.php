<?php

namespace FreshAdvance\Invoice\DataType;

use OxidEsales\Eshop\Application\Model\Order;
use OxidEsales\Eshop\Application\Model\Shop;

interface InvoiceDataInterface
{
    public function getOrder(): Order;

    public function getShop(): Shop;

    public function getLanguageId(): int;

    /**
     * Path to generated invoice file in the filesystem
     */
    public function getInvoicePath(): string;

    /**
     * The filename for downloadable file
     */
    public function getInvoiceFilename(): string;

    public function getInvoiceConfiguration(): InvoiceConfigurationInterface;
}
