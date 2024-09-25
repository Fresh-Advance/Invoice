<?php

declare(strict_types=1);

$aLang = [];

require __DIR__ . "/../../../translations/lt/module_lt_lang.php";

$aLang = array_merge($aLang, [
    'FA_INVOICE_FORM_SIGNER' => 'Sąskaitą išrašė',
    'FA_INVOICE_FORM_NUMBER' => 'Sąskaitos Nr.',
    'FA_INVOICE_FORM_DATE' => 'Sąskaitos išrašymo data',
    'FA_INVOICE_FORM_SAVE_DATA' => 'Generuoti naują dokumentą',

    'FA_INVOICE_FORM_DOWNLOAD' => 'Atsisiusti Sąskaitą',

    # Module settings main
    'SHOP_MODULE_GROUP_fa_invoice_main' => 'Invoice document',
    'SHOP_MODULE_fa_invoice_FilenamePrefix' => 'Filename Prefix',
    'SHOP_MODULE_fa_invoice_IsForArchive' => 'Document is for archive (PDFA)',
    'SHOP_MODULE_fa_invoice_InvoiceDateFormat' => 'Default invoice date format',

    # Module settings layout
    'SHOP_MODULE_GROUP_fa_invoice_layout' => 'Invoice document layout',
    'SHOP_MODULE_fa_invoice_MarginTop' => 'Margin top (e.g. 10px)',
    'SHOP_MODULE_fa_invoice_MarginBottom' => 'Margin bottom (e.g. 10px)',
    'SHOP_MODULE_fa_invoice_MarginLeft' => 'Margin left (e.g. 10px)',
    'SHOP_MODULE_fa_invoice_MarginRight' => 'Margin right (e.g. 10px)',
    'SHOP_MODULE_fa_invoice_DocumentHeader' => 'Document header (HTML with simple inline CSS possible)',
    'SHOP_MODULE_fa_invoice_DocumentFooter' => 'Document footer (HTML with simple inline CSS possible)',

    # Module settings invoice numbering
    'SHOP_MODULE_GROUP_fa_invoice_numbering' => 'Invoice numbering',
    'SHOP_MODULE_fa_invoice_InvoiceNumberUpdate' => 'Update order invoice number on invoice creation',
    'SHOP_MODULE_fa_invoice_InvoiceNumberFormat' => 'Invoice number format',

    # Module settings invoice mails
    'SHOP_MODULE_GROUP_fa_invoice_emails' => 'Email options',
    'SHOP_MODULE_fa_invoice_SendInvoiceOnUserOrderEmail' => 'Generate and attach invoice to Customer order confirmation email',
]);
