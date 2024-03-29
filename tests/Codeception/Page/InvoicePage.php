<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Tests\Codeception\Page;

use OxidEsales\Codeception\Admin\Order\OrderList;
use OxidEsales\Codeception\Page\Page;

class InvoicePage extends Page
{
    use OrderList;

    public $invoiceDateField = "//input[@name='invoice[date]']";
    public $invoiceNumberField = "//input[@name='invoice[number]']";
    public $invoiceSignerField = "//input[@name='invoice[signer]']";
    public $invoiceDataSaveButton = "//input[@name='saveData']";
    public $invoiceDownloadButton = "//input[@name='download']";
}
