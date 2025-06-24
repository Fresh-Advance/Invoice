<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\InvoiceData\Settings;

interface ModuleSettingsInterface
{
    public function getFileNameFormat(): string;

    // todo: split into separate interface, it should be in Pdf namespace
    public function isForArchive(): bool;

    public function getInvoiceNumberFormat(): string;

    public function getInvoiceDateFormat(): string;
}
