<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\InvoiceData\Settings;

interface FormatSettingsInterface
{
    public function getFileNameFormat(): string;

    public function getInvoiceNumberFormat(): string;

    public function getInvoiceDateFormat(): string;
}
