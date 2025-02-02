<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Email\Settings;

interface EmailSettingsInterface
{
    public function isSendInvoiceOnUserOrderEmailActive(): bool;

    public function isSendInvoiceOnOwnerOrderEmailActive(): bool;

    public function getUserOrderEmailInvoiceFilenameFormat(): string;

    public function getOwnerOrderEmailInvoiceFilenameFormat(): string;
}
