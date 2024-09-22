<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Tests\Unit\Service;

use FreshAdvance\Invoice\DataType\InvoiceConfigurationInterface;
use FreshAdvance\Invoice\Repository\InvoiceConfigurationRepositoryInterface;
use FreshAdvance\Invoice\Repository\OrderRepositoryInterface;
use FreshAdvance\Invoice\Repository\ShopRepositoryInterface;
use FreshAdvance\Invoice\Service\Invoice;
use FreshAdvance\Invoice\Settings\ConfigInterface;
use FreshAdvance\Invoice\Settings\ModuleSettings;
use OxidEsales\Eshop\Application\Model\Order as OrderModel;
use OxidEsales\Eshop\Application\Model\Shop as ShopModel;
use PHPUnit\Framework\TestCase;

/**
 * @covers \FreshAdvance\Invoice\Service\Invoice
 */
class InvoiceTest extends TestCase
{
    public function testGetInvoiceData(): void
    {
        $orderStub = $this->createConfiguredMock(OrderModel::class, [
            'getShopId' => 3,
            'getId' => 'someOrderId'
        ]);

        $orderServiceMock = $this->createMock(OrderRepositoryInterface::class);
        $orderServiceMock->method('getByOrderId')->with('someOrderId')->willReturn($orderStub);

        $shopStub = $this->createStub(ShopModel::class);
        $shopServiceMock = $this->createMock(ShopRepositoryInterface::class);
        $shopServiceMock->method('getByShopId')->with(3)->willReturn($shopStub);

        $shopConfigMock = $this->createMock(ConfigInterface::class);
        $shopConfigMock->method('getShopDefaultLanguageId')->with(3)->willReturn(5);

        $invoiceConfigurationStub = $this->createStub(InvoiceConfigurationInterface::class);
        $repositoryMock = $this->createMock(InvoiceConfigurationRepositoryInterface::class);
        $repositoryMock->method('getByOrderId')
            ->with('someOrderId')
            ->willReturn($invoiceConfigurationStub);

        $moduleSettingsStub = $this->createConfiguredMock(ModuleSettings::class, [
            'getFilePrefix' => 'prefix'
        ]);

        $sut = new Invoice(
            orderRepository: $orderServiceMock,
            shopService: $shopServiceMock,
            shopConfig: $shopConfigMock,
            moduleContext: $this->createConfiguredMock(
                \FreshAdvance\Invoice\Settings\Context::class,
                ['getInvoicesPath' => 'someRootPath']
            ),
            invoiceConfigRepo: $repositoryMock,
            moduleSettings: $moduleSettingsStub
        );

        $result = $sut->getInvoiceDataByOrderId('someOrderId');

        $this->assertSame($orderStub, $result->getOrder());
        $this->assertSame($shopStub, $result->getShop());
        $this->assertSame(5, $result->getLanguageId());
        $this->assertSame('someRootPath/so/someOrderId.pdf', $result->getInvoicePath());
        $this->assertSame($invoiceConfigurationStub, $result->getInvoiceConfiguration());
    }

    public function testSaveOrderInvoiceData(): void
    {
        $configurationStub = $this->createStub(InvoiceConfigurationInterface::class);

        $repositoryMock = $this->createMock(InvoiceConfigurationRepositoryInterface::class);
        $repositoryMock->expects($this->atLeastOnce())
            ->method('save')
            ->with($configurationStub);

        $moduleSettingsStub = $this->createConfiguredMock(ModuleSettings::class, [
            'getFilePrefix' => 'prefix'
        ]);

        $sut = new Invoice(
            orderRepository: $this->createStub(OrderRepositoryInterface::class),
            shopService: $this->createStub(ShopRepositoryInterface::class),
            shopConfig: $this->createStub(ConfigInterface::class),
            moduleContext: $this->createStub(\FreshAdvance\Invoice\Settings\Context::class),
            invoiceConfigRepo: $repositoryMock,
            moduleSettings: $moduleSettingsStub
        );

        $sut->saveOrderInvoiceData($configurationStub);
    }

    public function testGetInvoiceFileName(): void
    {
        $orderId = uniqid();
        $invoiceNumber = uniqid();
        $formattedInvoiceNumber = uniqid();

        $configurationMock = $this->createMock(InvoiceConfigurationInterface::class);
        $configurationMock->method('getOrderId')->willReturn($orderId);
        $configurationMock->method('getFormattedNumber')->with($invoiceNumber)->willReturn($formattedInvoiceNumber);

        $sut = new Invoice(
            orderRepository: $orderRepositoryMock = $this->createMock(OrderRepositoryInterface::class),
            shopService: $this->createStub(ShopRepositoryInterface::class),
            shopConfig: $this->createStub(ConfigInterface::class),
            moduleContext: $this->createStub(\FreshAdvance\Invoice\Settings\Context::class),
            invoiceConfigRepo: $this->createStub(InvoiceConfigurationRepositoryInterface::class),
            moduleSettings: $this->createConfiguredMock(ModuleSettings::class, [
                'getFilePrefix' => 'prefix-'
            ])
        );

        $orderRepositoryMock->method('getInvoiceNumberByOrderId')
            ->with($orderId)
            ->willReturn($invoiceNumber);

        $this->assertSame(
            "prefix-" . $formattedInvoiceNumber . ".pdf",
            $sut->getInvoiceFileName($configurationMock)
        );
    }
}
