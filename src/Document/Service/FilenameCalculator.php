<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Document\Service;

use FreshAdvance\Invoice\DataType\InvoiceDataInterface;

class FilenameCalculator implements FilenameCalculatorInterface
{
    public function calculateByFormat(string $format, InvoiceDataInterface $invoiceData): string
    {
        $order = $invoiceData->getOrder();

        /** @var string $format */
        $format = preg_replace_callback(
            '/<order:(\w+)>/',
            function ($matches) use ($order): string {
                /** @var int|string|null $value */
                $value = $order->getFieldData($matches[1]);
                return (string)$value;
            },
            $format
        );

        $invoiceConfiguration = $invoiceData->getInvoiceConfiguration();
        /** @var int|string|null $billNr */
        $billNr = $order->getFieldData('oxbillnr');
        $invoiceNumber = $invoiceConfiguration->getFormattedNumber((string)$billNr);

        return str_replace('<invoiceNumber>', $invoiceNumber, $format);
    }
}
