<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Tests\Unit\Service;

use FreshAdvance\Invoice\DataType\InvoiceConfiguration;
use FreshAdvance\Invoice\DataType\InvoiceConfigurationInterface;
use FreshAdvance\Invoice\Repository\InvoiceConfigurationRepositoryInterface;
use FreshAdvance\Invoice\Repository\OrderRepositoryInterface;
use FreshAdvance\Invoice\Repository\ShopRepositoryInterface;
use FreshAdvance\Invoice\Settings\Context;
use FreshAdvance\Invoice\Service\Invoice;
use FreshAdvance\Invoice\Settings\ModuleSettings;
use FreshAdvance\Invoice\Settings\ConfigInterface;
use OxidEsales\Eshop\Application\Model\Order as OrderModel;
use OxidEsales\Eshop\Application\Model\Shop as ShopModel;
use OxidEsales\Eshop\Core\Config;
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
            orderService: $orderServiceMock,
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
            orderService: $this->createStub(OrderRepositoryInterface::class),
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
        $configurationStub = $this->createConfiguredMock(InvoiceConfiguration::class, [
            'getNumber' => 'someNumber'
        ]);

        $moduleSettingsStub = $this->createConfiguredMock(ModuleSettings::class, [
            'getFilePrefix' => 'prefix-'
        ]);

        $sut = new Invoice(
            orderService: $this->createStub(OrderRepositoryInterface::class),
            shopService: $this->createStub(ShopRepositoryInterface::class),
            shopConfig: $this->createStub(ConfigInterface::class),
            moduleContext: $this->createStub(\FreshAdvance\Invoice\Settings\Context::class),
            invoiceConfigRepo: $this->createStub(InvoiceConfigurationRepositoryInterface::class),
            moduleSettings: $moduleSettingsStub
        );

        $this->assertSame("prefix-someNumber.pdf", $sut->getInvoiceFileName($configurationStub));
    }
}
