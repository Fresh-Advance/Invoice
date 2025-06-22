<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Tests\Unit\Pdf\Service;

use FreshAdvance\Invoice\InvoiceData\DataType\InvoiceDataInterface;
use FreshAdvance\Invoice\Pdf\Service\FormatCalculator;
use OxidEsales\Eshop\Application\Model\Order;

class FormatCalculatorTest extends \PHPUnit\Framework\TestCase
{
    public function testOrderFieldsAvailableInFormat(): void
    {
        $sut = new FormatCalculator();

        $invoiceData = $this->createConfiguredMock(InvoiceDataInterface::class, [
            'getOrder' => $orderMock = $this->createMock(Order::class),
        ]);

        $orderMock->method('getFieldData')->willReturnMap([
            ['oxordernr', $orderNr = uniqid()],
            ['oxbillfname', $billName = uniqid()],
        ]);

        $format = 'invoice_<order:oxordernr>_<order:oxbillfname>.pdf';

        $this->assertEquals(
            'invoice_' . $orderNr . '_' . $billName . '.pdf',
            $sut->calculateByFormat($format, $invoiceData)
        );
    }
}
