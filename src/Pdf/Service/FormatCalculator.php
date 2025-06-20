<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Pdf\Service;

use FreshAdvance\Invoice\DataType\InvoiceDataInterface;

class FormatCalculator implements FormatCalculatorInterface
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

        return $format;
    }
}
