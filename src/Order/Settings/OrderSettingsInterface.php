<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

namespace FreshAdvance\Invoice\Order\Settings;

interface OrderSettingsInterface
{
    public function isOrderInvoiceNumberUpdateActive(): bool;
}
