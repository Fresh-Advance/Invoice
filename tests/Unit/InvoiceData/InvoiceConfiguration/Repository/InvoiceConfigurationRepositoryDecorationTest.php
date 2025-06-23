<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Tests\Unit\InvoiceData\InvoiceConfiguration\Repository;

use FreshAdvance\Invoice\InvoiceData\InvoiceConfiguration\DataType\InvoiceConfigurationInterface;
use FreshAdvance\Invoice\InvoiceData\InvoiceConfiguration\Exception\InvoiceConfigurationNotFound;
use FreshAdvance\Invoice\InvoiceData\InvoiceConfiguration\Repository\InvoiceConfigurationRepositoryDecoration;
use FreshAdvance\Invoice\InvoiceData\InvoiceConfiguration\Repository\InvoiceConfigurationRepositoryInterface;
use FreshAdvance\Invoice\Settings\ModuleSettingsInterface;
use PHPUnit\Framework\TestCase;

class InvoiceConfigurationRepositoryDecorationTest extends TestCase
{
    public function testDecorationProxiesToOriginalMethods(): void
    {
        $invoiceConfigurationStub = $this->createStub(InvoiceConfigurationInterface::class);

        $originalRepository = $this->createMock(
            InvoiceConfigurationRepositoryInterface::class
        );
        $originalRepository->method('getByOrderId')
            ->with($orderId = uniqid())
            ->willReturn($invoiceConfigurationStub);
        $originalRepository->expects($this->once())
            ->method('save')
            ->with($invoiceConfigurationStub);

        $sut = $this->getSut(
            invoiceConfigurationRepository: $originalRepository,
        );

        $this->assertSame($invoiceConfigurationStub, $sut->getByOrderId($orderId));
        $sut->save($invoiceConfigurationStub);
    }

    public function testDecorationReturnsInvoiceConfigurationWithDefaultsIfNoneFound(): void
    {
        $moduleSettingsStub = $this->createStub(ModuleSettingsInterface::class);
        $moduleSettingsStub->method('getInvoiceDateFormat')->willReturn($dateFormat = uniqid());
        $moduleSettingsStub->method('getInvoiceNumberFormat')->willReturn($numberFormat = uniqid());

        $originalRepositoryMock = $this->createMock(
            InvoiceConfigurationRepositoryInterface::class
        );
        $originalRepositoryMock->method('getByOrderId')
            ->with($orderId = uniqid())
            ->willThrowException(new InvoiceConfigurationNotFound());

        $sut = $this->getSut(
            invoiceConfigurationRepository: $originalRepositoryMock,
            moduleSettings: $moduleSettingsStub,
        );

        $configuration = $sut->getByOrderId($orderId);

        $this->assertSame($orderId, $configuration->getOrderId());
        $this->assertSame($dateFormat, $configuration->getDate());
        $this->assertSame($numberFormat, $configuration->getNumber());
    }

    private function getSut(
        InvoiceConfigurationRepositoryInterface $invoiceConfigurationRepository = null,
        ModuleSettingsInterface $moduleSettings = null,
    ): InvoiceConfigurationRepositoryInterface {
        $invoiceConfigurationRepository ??= $this->createStub(InvoiceConfigurationRepositoryInterface::class);

        return new InvoiceConfigurationRepositoryDecoration(
            originalRepository: $invoiceConfigurationRepository,
            moduleSettings: $moduleSettings ?? $this->createStub(ModuleSettingsInterface::class),
        );
    }
}
