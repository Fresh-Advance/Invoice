<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Tests\Unit\Service;

use FreshAdvance\Invoice\DataType\InvoiceConfigurationInterface;
use FreshAdvance\Invoice\Order\Repository\OrderRepositoryInterface;
use FreshAdvance\Invoice\Repository\InvoiceConfigurationRepositoryInterface;
use FreshAdvance\Invoice\Repository\ShopRepositoryInterface;
use FreshAdvance\Invoice\Service\Invoice;
use FreshAdvance\Invoice\Settings\ConfigInterface;
use FreshAdvance\Invoice\Settings\ContextInterface;
use FreshAdvance\Invoice\Settings\ModuleSettings;
use FreshAdvance\Invoice\Settings\ModuleSettingsInterface;
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


        $sut = $this->getSut(
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

    public function testGetDefaultInvoiceData(): void
    {
        $sut = $this->getSut(
            orderRepository: $this->createConfiguredMock(OrderRepositoryInterface::class, [
                'getByOrderId' => $this->createConfiguredMock(OrderModel::class, [
                    'getShopId' => 3,
                    'getId' => uniqid()
                ])
            ]),
            invoiceConfigRepo: $this->createConfiguredMock(InvoiceConfigurationRepositoryInterface::class, [
                'getByOrderId' => null
            ]),
            moduleSettings: $this->createConfiguredMock(ModuleSettingsInterface::class, [
                'getInvoiceDateFormat' => $dateFormat = uniqid(),
                'getInvoiceNumberFormat' => $numberFormat = uniqid(),
            ])
        );

        $result = $sut->getInvoiceDataByOrderId(uniqid());

        $configuration = $result->getInvoiceConfiguration();

        $this->assertSame($dateFormat, $configuration->getDate());
        $this->assertSame($numberFormat, $configuration->getNumber());
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

        $sut = $this->getSut(
            invoiceConfigRepo: $repositoryMock,
            moduleSettings: $moduleSettingsStub,
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

        $sut = $this->getSut(
            orderRepository: $orderRepositoryMock = $this->createMock(OrderRepositoryInterface::class),
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

    protected function getSut(
        OrderRepositoryInterface $orderRepository = null,
        ShopRepositoryInterface $shopService = null,
        ConfigInterface $shopConfig = null,
        ContextInterface $moduleContext = null,
        InvoiceConfigurationRepositoryInterface $invoiceConfigRepo = null,
        ModuleSettingsInterface $moduleSettings = null,
    ): Invoice {
        return new Invoice(
            orderRepository: $orderRepository ?? $this->createStub(OrderRepositoryInterface::class),
            shopService: $shopService ?? $this->createStub(ShopRepositoryInterface::class),
            shopConfig: $shopConfig ?? $this->createStub(ConfigInterface::class),
            moduleContext: $moduleContext ?? $this->createStub(ContextInterface::class),
            invoiceConfigRepo: $invoiceConfigRepo ?? $this->createStub(InvoiceConfigurationRepositoryInterface::class),
            moduleSettings: $moduleSettings ?? $this->createStub(ModuleSettingsInterface::class)
        );
    }
}
