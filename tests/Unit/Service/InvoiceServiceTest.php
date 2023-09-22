<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Tests\Unit\Service;

use FreshAdvance\Invoice\Service\InvoiceService;
use FreshAdvance\Invoice\Transition\Core\UtilsInterface;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

/**
 * @covers \FreshAdvance\Invoice\Service\InvoiceService
 */
class InvoiceServiceTest extends TestCase
{
    public function testFilenameHeaderSet(): void
    {
        $headerFileName = 'exampleFile.pdf';

        $utilsMock = $this->createPartialMock(UtilsInterface::class, ['setHeader']);
        $utilsMock->expects($this->any())
            ->method('setHeader')
            ->willReturnCallback(function ($value) use ($headerFileName) {
                if (preg_match("@filename={$headerFileName}@msi", $value)) {
                    throw new \Exception("match");
                }
            });

        $this->expectExceptionMessage("match");

        $sut = new InvoiceService($utilsMock);
        $sut->triggerInvoiceFileDownload($headerFileName, "examplePath.pdf");
    }

    public function testCorrectFileContentShown(): void
    {
        $tempDirectory = vfsStream::setup('root', null, [
            'filename.pdf' => 'someFileContent'
        ]);

        $headerFilename = 'exampleFile.pdf';
        $filePath = $tempDirectory->url() . '/filename.pdf';

        $utilsMock = $this->createPartialMock(UtilsInterface::class, ['setHeader', 'showMessageAndExit']);
        $utilsMock->expects($this->atLeastOnce())->method('showMessageAndExit')->with('someFileContent');

        $sut = new InvoiceService($utilsMock);
        $sut->triggerInvoiceFileDownload($headerFilename, $filePath);
    }
}
