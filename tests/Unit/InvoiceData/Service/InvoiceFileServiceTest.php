<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Tests\Unit\Invoice\Service;

use FreshAdvance\Invoice\InvoiceData\DataType\InvoiceDataInterface;
use FreshAdvance\Invoice\InvoiceData\Service\InvoiceFileService;
use FreshAdvance\Invoice\InvoiceData\Service\InvoiceFileServiceInterface;
use FreshAdvance\Invoice\Pdf\Service\FilenameCalculatorInterface;
use FreshAdvance\Invoice\Settings\ModuleSettingsInterface;
use FreshAdvance\Invoice\Transput\UtilsProxy;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

/**
 * @covers \FreshAdvance\Invoice\InvoiceData\Service\InvoiceFileService
 */
class InvoiceFileServiceTest extends TestCase
{
    public function testFilenameHeaderSet(): void
    {
        $headerFileName = 'exampleFile.pdf';

        $utilsMock = $this->createPartialMock(UtilsProxy::class, ['setHeader']);
        $utilsMock->expects($this->any())
            ->method('setHeader')
            ->willReturnCallback(function ($value) use ($headerFileName) {
                if (preg_match("@filename={$headerFileName}@msi", $value)) {
                    throw new \Exception("match");
                }
            });

        $this->expectExceptionMessage("match");

        $sut = $this->getSut(
            utils: $utilsMock,
        );

        $sut->triggerInvoiceFileDownload($headerFileName, "examplePath.pdf");
    }

    public function testCorrectFileContentShown(): void
    {
        $tempDirectory = vfsStream::setup('root', null, [
            'filename.pdf' => 'someFileContent'
        ]);

        $headerFilename = 'exampleFile.pdf';
        $filePath = $tempDirectory->url() . '/filename.pdf';

        $utilsMock = $this->createPartialMock(UtilsProxy::class, ['setHeader', 'showMessageAndExit']);
        $utilsMock->expects($this->atLeastOnce())->method('showMessageAndExit')->with('someFileContent');

        $sut = $this->getSut(
            utils: $utilsMock,
        );
        $sut->triggerInvoiceFileDownload($headerFilename, $filePath);
    }

    public function testGetInvoiceFileName(): void
    {
        $sut = $this->getSut(
            moduleSettings: $this->createConfiguredMock(ModuleSettingsInterface::class, [
                'getFileNameFormat' => $fileNameFormat = uniqid(),
            ]),
            filenameCalculator: $filenameCalculatorMock = $this->createMock(FilenameCalculatorInterface::class),
        );

        $invoiceDataStub = $this->createMock(InvoiceDataInterface::class);
        $filenameCalculatorMock->method('calculateByFormat')
            ->with($fileNameFormat, $invoiceDataStub)
            ->willReturn($formattedFileName = uniqid());

        $this->assertSame($formattedFileName, $sut->getInvoiceFileName($invoiceDataStub));
    }

    private function getSut(
        UtilsProxy $utils = null,
        ModuleSettingsInterface $moduleSettings = null,
        FilenameCalculatorInterface $filenameCalculator = null,
    ): InvoiceFileServiceInterface {
        return new InvoiceFileService(
            utils: $utils ?? $this->createStub(UtilsProxy::class),
            moduleSettings: $moduleSettings ?? $this->createStub(ModuleSettingsInterface::class),
            filenameCalculator: $filenameCalculator ?? $this->createStub(FilenameCalculatorInterface::class),
        );
    }
}
