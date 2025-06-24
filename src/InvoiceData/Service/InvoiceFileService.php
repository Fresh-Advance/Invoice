<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\InvoiceData\Service;

use FreshAdvance\Invoice\InvoiceData\DataType\InvoiceDataInterface;
use FreshAdvance\Invoice\Pdf\Service\FilenameCalculatorInterface;
use FreshAdvance\Invoice\Settings\ModuleSettingsInterface;
use FreshAdvance\Invoice\Transput\ResponseInterface;

class InvoiceFileService implements InvoiceFileServiceInterface
{
    public function __construct(
        protected readonly ResponseInterface $utils,
        protected readonly ModuleSettingsInterface $moduleSettings,
        protected readonly FilenameCalculatorInterface $filenameCalculator,
    ) {
    }

    public function triggerInvoiceFileDownload(string $fileName, string $filePath): void
    {
        $this->utils->setHeader('Content-Type: application/pdf');
        $this->utils->setHeader('Content-Disposition:attachment;filename=' . $fileName);

        /** @var string $fileContent */
        $fileContent = file_get_contents($filePath) ?: '';
        $this->utils->showMessageAndExit($fileContent);
    }

    public function getInvoiceFileName(InvoiceDataInterface $invoiceData): string
    {
        return $this->filenameCalculator->calculateByFormat(
            $this->moduleSettings->getFileNameFormat(),
            $invoiceData
        );
    }
}
