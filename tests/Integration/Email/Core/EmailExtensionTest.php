<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Tests\Integration\Email\Core;

use FreshAdvance\Invoice\Email\Core\EmailExtension;
use FreshAdvance\Invoice\Email\Settings\EmailSettingsInterface;
use FreshAdvance\Invoice\InvoiceData\DataType\InvoiceDataInterface;
use FreshAdvance\Invoice\InvoiceData\Service\Invoice;
use FreshAdvance\Invoice\Pdf\InvoiceGeneratorInterface;
use FreshAdvance\Invoice\Pdf\Service\FilenameCalculator;
use FreshAdvance\Invoice\Pdf\Service\FilenameCalculatorInterface;
use OxidEsales\Eshop\Application\Model\Order as OrderModel;
use OxidEsales\EshopCommunity\Tests\Integration\IntegrationTestCase;

/** @covers \FreshAdvance\Invoice\Email\Core\EmailExtension */
class EmailExtensionTest extends IntegrationTestCase
{
    public function testInvoiceGeneratedAndAttachedToUserEmailWithOptionOn(): void
    {
        $sut = $this->createPartialMock(
            EmailExtension::class,
            ['faCallParentSend', 'faCallParentSendOrderEmailToUser', 'getServiceFromContainer', 'addAttachment']
        );

        $sut->method('getServiceFromContainer')->willReturnMap(
            $this->getDIConfiguration(
                invoiceDataService: $invoiceDataService = $this->createMock(Invoice::class),
                invoiceGenerator: $invoiceGeneratorSpy = $this->createMock(InvoiceGeneratorInterface::class),
                invoiceFilenameCalculator: $filenameCalculatorMock = $this->createMock(
                    FilenameCalculator::class
                ),
                emailSettings: $this->createConfiguredMock(EmailSettingsInterface::class, [
                    'isSendInvoiceOnUserOrderEmailActive' => true,
                    'getUserOrderEmailInvoiceFilenameFormat' => $fileNameFormat = uniqid()
                ]),
            )
        );

        $order = $this->createConfiguredMock(OrderModel::class, ['getId' => $orderId = uniqid()]);
        $invoiceDataService->method('getInvoiceDataByOrderId')
            ->with($orderId)
            ->willReturn(
                $invoiceDataStub = $this->createConfiguredMock(InvoiceDataInterface::class, [
                    'getInvoicePath' => $invoicePath = uniqid()
                ])
            );

        $filenameCalculatorMock->method('calculateByFormat')
            ->with($fileNameFormat, $invoiceDataStub)
            ->willReturn($calculatedFileName = uniqid());

        $invoiceGeneratorSpy->expects($this->once())->method('generate')->with($invoiceDataStub);

        $sut->method('faCallParentSendOrderEmailToUser')
            ->with($order, $emailSubject = uniqid())
            ->willReturn($parentOrderEmailSendResult = uniqid());
        $this->assertSame($parentOrderEmailSendResult, $sut->sendOrderEmailToUser($order, $emailSubject));

        $sut->expects($this->once())->method('addAttachment')
            ->with($invoicePath, $calculatedFileName);
        $sut->method('faCallParentSend')->willReturn($parentSendResult = uniqid());

        $this->assertSame($parentSendResult, $sut->send());

        // check second send will not trigger the attachment again
        $sut->send();
    }

    public function testUserInvoiceNotGeneratedAndNotAttachedWithOptionOff(): void
    {
        $sut = $this->createPartialMock(
            \FreshAdvance\Invoice\Email\Core\EmailExtension::class,
            ['faCallParentSend', 'faCallParentSendOrderEmailToUser', 'getServiceFromContainer', 'addAttachment']
        );
        $sut->method('getServiceFromContainer')->willReturnMap(
            $this->getDIConfiguration(
                invoiceGenerator: $invoiceGeneratorSpy = $this->createMock(InvoiceGeneratorInterface::class),
                emailSettings: $this->createConfiguredMock(EmailSettingsInterface::class, [
                    'isSendInvoiceOnUserOrderEmailActive' => false
                ])
            )
        );

        $invoiceGeneratorSpy->expects($this->never())->method('generate');

        $order = $this->createConfiguredMock(OrderModel::class, ['getId' => uniqid()]);

        $sut->method('faCallParentSendOrderEmailToUser')
            ->with($order, $emailSubject = uniqid())
            ->willReturn($parentOrderEmailSendResult = uniqid());
        $this->assertSame($parentOrderEmailSendResult, $sut->sendOrderEmailToUser($order, $emailSubject));

        $sut->method('faCallParentSend')->willReturn($parentSendResult = uniqid());
        $sut->expects($this->never())->method('addAttachment');
        $this->assertSame($parentSendResult, $sut->send());
    }

    public function testInvoiceGeneratedAndAttachedToOwnerEmailWithOptionOn(): void
    {
        $sut = $this->createPartialMock(
            EmailExtension::class,
            ['faCallParentSend', 'faCallParentSendOrderEmailToOwner', 'getServiceFromContainer', 'addAttachment']
        );

        $sut->method('getServiceFromContainer')->willReturnMap(
            $this->getDIConfiguration(
                invoiceDataService: $invoiceDataService = $this->createMock(Invoice::class),
                invoiceGenerator: $invoiceGeneratorSpy = $this->createMock(InvoiceGeneratorInterface::class),
                invoiceFilenameCalculator: $filenameCalculatorMock = $this->createMock(
                    FilenameCalculator::class
                ),
                emailSettings: $this->createConfiguredMock(EmailSettingsInterface::class, [
                    'isSendInvoiceOnOwnerOrderEmailActive' => true,
                    'getOwnerOrderEmailInvoiceFilenameFormat' => $fileNameFormat = uniqid()
                ]),
            )
        );

        $order = $this->createConfiguredMock(OrderModel::class, ['getId' => $orderId = uniqid()]);
        $invoiceDataService->method('getInvoiceDataByOrderId')
            ->with($orderId)
            ->willReturn(
                $invoiceDataStub = $this->createConfiguredMock(InvoiceDataInterface::class, [
                    'getInvoicePath' => $invoicePath = uniqid()
                ])
            );

        $filenameCalculatorMock->method('calculateByFormat')
            ->with($fileNameFormat, $invoiceDataStub)
            ->willReturn($calculatedFileName = uniqid());

        $invoiceGeneratorSpy->expects($this->once())->method('generate')->with($invoiceDataStub);

        $sut->method('faCallParentSendOrderEmailToOwner')
            ->with($order, $emailSubject = uniqid())
            ->willReturn($parentOrderEmailSendResult = uniqid());
        $this->assertSame($parentOrderEmailSendResult, $sut->sendOrderEmailToOwner($order, $emailSubject));

        $sut->expects($this->once())->method('addAttachment')
            ->with($invoicePath, $calculatedFileName);
        $sut->method('faCallParentSend')->willReturn($parentSendResult = uniqid());

        $this->assertSame($parentSendResult, $sut->send());

        // check second send will not trigger the attachment again
        $sut->send();
    }

    public function testOwnerInvoiceNotGeneratedAndNotAttachedWithOptionOff(): void
    {
        $sut = $this->createPartialMock(
            \FreshAdvance\Invoice\Email\Core\EmailExtension::class,
            ['faCallParentSend', 'faCallParentSendOrderEmailToOwner', 'getServiceFromContainer', 'addAttachment']
        );
        $sut->method('getServiceFromContainer')->willReturnMap(
            $this->getDIConfiguration(
                invoiceGenerator: $invoiceGeneratorSpy = $this->createMock(InvoiceGeneratorInterface::class),
                emailSettings: $this->createConfiguredMock(EmailSettingsInterface::class, [
                    'isSendInvoiceOnOwnerOrderEmailActive' => false
                ])
            )
        );

        $invoiceGeneratorSpy->expects($this->never())->method('generate');

        $order = $this->createConfiguredMock(OrderModel::class, ['getId' => uniqid()]);

        $sut->method('faCallParentSendOrderEmailToOwner')
            ->with($order, $emailSubject = uniqid())
            ->willReturn($parentOrderEmailSendResult = uniqid());
        $this->assertSame($parentOrderEmailSendResult, $sut->sendOrderEmailToOwner($order, $emailSubject));

        $sut->method('faCallParentSend')->willReturn($parentSendResult = uniqid());
        $sut->expects($this->never())->method('addAttachment');
        $this->assertSame($parentSendResult, $sut->send());
    }

    public function testInvoiceGeneratedOnceAndAttachedToBothEmailsWithOptionsOn(): void
    {
        $sut = $this->createPartialMock(
            EmailExtension::class,
            [
                'faCallParentSend',
                'faCallParentSendOrderEmailToUser',
                'faCallParentSendOrderEmailToOwner',
                'getServiceFromContainer',
                'addAttachment'
            ]
        );

        $sut->method('getServiceFromContainer')->willReturnMap(
            $this->getDIConfiguration(
                invoiceDataService: $invoiceDataService = $this->createMock(Invoice::class),
                invoiceGenerator: $invoiceGeneratorSpy = $this->createMock(InvoiceGeneratorInterface::class),
                invoiceFilenameCalculator: $filenameCalculatorMock = $this->createMock(
                    FilenameCalculator::class
                ),
                emailSettings: $this->createConfiguredMock(EmailSettingsInterface::class, [
                    'isSendInvoiceOnUserOrderEmailActive' => true,
                    'isSendInvoiceOnOwnerOrderEmailActive' => true,
                    'getOwnerOrderEmailInvoiceFilenameFormat' => $ownerFileNameFormat = uniqid(),
                    'getUserOrderEmailInvoiceFilenameFormat' => $userFileNameFormat = uniqid(),
                ]),
            )
        );

        $order = $this->createConfiguredMock(OrderModel::class, ['getId' => $orderId = uniqid()]);
        $invoiceDataService->method('getInvoiceDataByOrderId')
            ->with($orderId)
            ->willReturn(
                $invoiceDataStub = $this->createConfiguredMock(InvoiceDataInterface::class, [
                    'getInvoicePath' => $invoicePath = uniqid()
                ])
            );

        $filenameCalculatorMock->method('calculateByFormat')->willReturnMap([
            [$userFileNameFormat, $invoiceDataStub, $calculatedUserFileName = uniqid()],
            [$ownerFileNameFormat, $invoiceDataStub, $calculatedOwnerFileName = uniqid()],
        ]);

        $invoiceGeneratorSpy->expects($this->once())->method('generate')->with($invoiceDataStub);

        $sut->expects($matcher = $this->exactly(2))->method('addAttachment')
            ->willReturnCallback(
                function (
                    $path,
                    $name
                ) use (
                    $matcher,
                    $invoicePath,
                    $calculatedUserFileName,
                    $calculatedOwnerFileName
                ) {
                    switch ($matcher->numberOfInvocations()) {
                        case 1:
                            $this->assertSame($path, $invoicePath);
                            $this->assertSame($name, $calculatedUserFileName);
                            break;
                        case 2:
                            $this->assertSame($path, $invoicePath);
                            $this->assertSame($name, $calculatedOwnerFileName);
                            break;
                    }
                }
            );

        $sut->method('faCallParentSendOrderEmailToUser')
            ->with($order, $emailSubject = uniqid())
            ->willReturn($parentOrderUserEmailSendResult = uniqid());
        $this->assertSame($parentOrderUserEmailSendResult, $sut->sendOrderEmailToUser($order, $emailSubject));
        $sut->send();

        $sut->method('faCallParentSendOrderEmailToOwner')
            ->with($order, $emailSubject = uniqid())
            ->willReturn($parentOrderOwnerEmailSendResult = uniqid());
        $this->assertSame($parentOrderOwnerEmailSendResult, $sut->sendOrderEmailToOwner($order, $emailSubject));
        $sut->send();

        // check third send will not trigger the attachment again
        $sut->send();
    }

    public function testInvoiceNotGeneratedAndNotAttachedWithOptionsOff(): void
    {
        $sut = $this->createPartialMock(
            \FreshAdvance\Invoice\Email\Core\EmailExtension::class,
            [
                'faCallParentSend',
                'faCallParentSendOrderEmailToUser',
                'faCallParentSendOrderEmailToOwner',
                'getServiceFromContainer',
                'addAttachment'
            ]
        );
        $sut->method('getServiceFromContainer')->willReturnMap(
            $this->getDIConfiguration(
                invoiceGenerator: $invoiceGeneratorSpy = $this->createMock(InvoiceGeneratorInterface::class),
                emailSettings: $this->createConfiguredMock(EmailSettingsInterface::class, [
                    'isSendInvoiceOnUserOrderEmailActive' => false,
                    'isSendInvoiceOnOwnerOrderEmailActive' => false,
                ])
            )
        );

        $invoiceGeneratorSpy->expects($this->never())->method('generate');
        $sut->expects($this->never())->method('addAttachment');

        $order = $this->createConfiguredMock(OrderModel::class, ['getId' => uniqid()]);

        $sut->sendOrderEmailToUser($order, uniqid());
        $sut->sendOrderEmailToOwner($order, uniqid());

        $sut->send();
    }

    protected function getDIConfiguration(
        Invoice $invoiceDataService = null,
        InvoiceGeneratorInterface $invoiceGenerator = null,
        FilenameCalculatorInterface $invoiceFilenameCalculator = null,
        EmailSettingsInterface $emailSettings = null,
    ): array {
        return [
            [
                Invoice::class,
                $invoiceDataService ?? $this->createStub(Invoice::class)
            ],
            [
                InvoiceGeneratorInterface::class,
                $invoiceGenerator ?? $this->createStub(InvoiceGeneratorInterface::class)
            ],
            [
                FilenameCalculatorInterface::class,
                $invoiceFilenameCalculator ?? $this->createStub(FilenameCalculatorInterface::class)
            ],
            [
                EmailSettingsInterface::class,
                $emailSettings ?? $this->createStub(EmailSettingsInterface::class)
            ],
        ];
    }
}
