<?php

declare(strict_types=1);

$aLang = [];

require __DIR__ . "/../../../translations/en/module_en_lang.php";

$aLang = array_merge($aLang, [
    'FA_INVOICE_FORM_SIGNER' => 'Invoiced by',
    'FA_INVOICE_FORM_NUMBER' => 'Invoice No',
    'FA_INVOICE_FORM_DATE' => 'Invoice Date',
    'FA_INVOICE_FORM_SAVE_DATA' => 'Generate new Invoice document',

    'FA_INVOICE_FORM_DOWNLOAD' => 'Download Invoice',

    # Module settings main
    'SHOP_MODULE_GROUP_fa_invoice_main' => 'Invoice document',
    'SHOP_MODULE_fa_invoice_FilenamePrefix' => 'Filename Prefix',
    'SHOP_MODULE_fa_invoice_IsForArchive' => 'Document is for archive (PDFA)',
    'SHOP_MODULE_fa_invoice_InvoiceDateFormat' => 'Default invoice date format',

    # Module settings layout
    'SHOP_MODULE_GROUP_fa_invoice_layout' => 'Invoice document layout',
    'SHOP_MODULE_fa_invoice_MarginTop' => 'Margin top',
    'SHOP_MODULE_fa_invoice_MarginBottom' => 'Margin bottom',
    'SHOP_MODULE_fa_invoice_MarginLeft' => 'Margin left',
    'SHOP_MODULE_fa_invoice_MarginRight' => 'Margin right',
    'SHOP_MODULE_fa_invoice_DocumentHeader' => 'Document header',
    'SHOP_MODULE_fa_invoice_DocumentFooter' => 'Document footer',

    # Module settings invoice numbering
    'SHOP_MODULE_GROUP_fa_invoice_numbering' => 'Invoice numbering',
    'SHOP_MODULE_fa_invoice_InvoiceNumberUpdate' => 'Update order invoice number on invoice creation',
    'SHOP_MODULE_fa_invoice_InvoiceNumberFormat' => 'Invoice number format',

    # Module settings invoice mails
    'SHOP_MODULE_GROUP_fa_invoice_emails' => 'Email options',
    'SHOP_MODULE_fa_invoice_SendInvoiceOnUserOrderEmail' => 'Generate and attach invoice to Customer order confirmation email',
]);
