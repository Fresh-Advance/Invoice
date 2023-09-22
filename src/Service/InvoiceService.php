<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Service;

use FreshAdvance\Invoice\Transition\Core\UtilsInterface;

class InvoiceService
{
    public function __construct(
        protected UtilsInterface $utils
    ) {
    }

    public function triggerInvoiceFileDownload($fileName, $filePath): void
    {
        $this->utils->setHeader('Content-Type: application/pdf');
        $this->utils->setHeader('Content-Disposition:attachment;filename=' . $fileName);

        /** @var string $fileContent */
        $fileContent = file_get_contents($filePath) ?: '';
        $this->utils->showMessageAndExit($fileContent);
    }
}
