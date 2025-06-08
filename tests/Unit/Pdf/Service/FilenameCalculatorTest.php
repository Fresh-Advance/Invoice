<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Tests\Unit\Pdf\Service;

use FreshAdvance\Invoice\DataType\InvoiceConfigurationInterface;
use FreshAdvance\Invoice\DataType\InvoiceDataInterface;
use FreshAdvance\Invoice\Pdf\Service\FilenameCalculator;
use OxidEsales\Eshop\Application\Model\Order;

class FilenameCalculatorTest extends \PHPUnit\Framework\TestCase
{
    public function testOrderFieldsAvailableInFormat(): void
    {
        $sut = new FilenameCalculator();

        $invoiceData = $this->createConfiguredMock(InvoiceDataInterface::class, [
            'getOrder' => $orderMock = $this->createMock(Order::class),
        ]);

        $orderMock->method('getFieldData')->willReturnMap([
            ['oxordernr', '123'],
            ['oxbillfname', 'Anton'],
        ]);

        $format = 'invoice_<order:oxordernr>_<order:oxbillfname>.pdf';

        $this->assertEquals('invoice_123_Anton.pdf', $sut->calculateByFormat($format, $invoiceData));
    }

    public function testInvoiceNumberAvailableInFormat(): void
    {
        $sut = new \FreshAdvance\Invoice\Pdf\Service\FilenameCalculator();

        $invoiceConfiguration = $this->createMock(InvoiceConfigurationInterface::class);
        $orderMock = $this->createMock(Order::class);

        $invoiceData = $this->createConfiguredMock(InvoiceDataInterface::class, [
            'getOrder' => $orderMock,
            'getInvoiceConfiguration' => $invoiceConfiguration,
        ]);

        $orderMock->method('getFieldData')->willReturnMap([
            ['oxbillnr', $billNr = uniqid()],
        ]);

        $invoiceConfiguration->method('getFormattedNumber')
            ->with($billNr)
            ->willReturn($formattedNumber = uniqid());

        $format = 'invoice_<invoiceNumber>.pdf';

        $this->assertEquals('invoice_' . $formattedNumber . '.pdf', $sut->calculateByFormat($format, $invoiceData));
    }
}
