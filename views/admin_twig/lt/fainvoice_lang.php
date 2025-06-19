<?php

declare(strict_types=1);

$aLang = [];

require __DIR__ . "/../../../translations/lt/module_lt_lang.php";

$aLang = array_merge($aLang, [
    'FA_INVOICE_FORM_TITLE_NEW_DOCUMENT' => 'Naujas sąskaitos dokumentas',
    'FA_INVOICE_FORM_TITLE_CURRENT_DOCUMENT' => 'Esamas dokumentas',

    'FA_INVOICE_FORM_SIGNER' => 'Sąskaitą išrašė',
    'FA_INVOICE_FORM_NUMBER' => 'Sąskaitos Nr.',
    'FA_INVOICE_FORM_DATE' => 'Sąskaitos išrašymo data',
    'FA_INVOICE_FORM_SAVE_DATA' => 'Generuoti naują dokumentą',
    'FA_INVOICE_FORM_CONFIRM_REGENERATE' => 'Ar tikrai norite pergeneruoti jau esantį dokumentą?',

    'FA_INVOICE_FORM_DOWNLOAD' => 'Atsisiusti Sąskaitą',

    # Module settings main
    'SHOP_MODULE_GROUP_fa_invoice_main' => 'Invoice document',
    'SHOP_MODULE_fa_invoice_FilenameFormat' => 'Filename for invoice downloadable in admin panel',
    'HELP_SHOP_MODULE_fa_invoice_FilenameFormat' => 'This setting is used to define the filename for the invoice downloadable in admin panel. <br><br><strong>Possible placeholders:</strong><br><br><strong>&lt;order:tableField&gt;</strong> format placeholder to include information from the oxorder table(eg. &lt;order:oxbillfname&gt;)<br><strong>&lt;invoiceNumber&gt;</strong> placeholder to include the invoice number',
    'SHOP_MODULE_fa_invoice_IsForArchive' => 'Document is for archive (PDFA)',
    'SHOP_MODULE_fa_invoice_InvoiceDateFormat' => 'Default invoice date format',
    'HELP_SHOP_MODULE_fa_invoice_InvoiceNumberFormat' => 'Invoice number format is responsible for decorating your Invoice number with some serie, and get the number like: SE-ABC-123 by using the format SE-ABC-%1$s. This setting is also used during filename generation as <invoiceNumber> placeholder.',

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
    'SHOP_MODULE_fa_invoice_SendInvoiceOnOwnerOrderEmail' => 'Generate and attach invoice to Owner order confirmation email',
    'SHOP_MODULE_fa_invoice_OwnerOrderEmailInvoiceFilenameFormat' => 'Filename for invoice attached to Owner order confirmation email',
    'HELP_SHOP_MODULE_fa_invoice_OwnerOrderEmailInvoiceFilenameFormat' => 'This setting is used to define the filename for the invoice attached to the Owner order confirmation email. <br><br><strong>Possible placeholders:</strong><br><br><strong>&lt;order:tableField&gt;</strong> format placeholder to include information from the oxorder table(eg. &lt;order:oxbillfname&gt; or &lt;order:oxbillnr&gt;)',
    'SHOP_MODULE_fa_invoice_SendInvoiceOnUserOrderEmail' => 'Generate and attach invoice to Customer order confirmation email',
    'SHOP_MODULE_fa_invoice_UserOrderEmailInvoiceFilenameFormat' => 'Filename for invoice attached to Customer order confirmation email',
    'HELP_SHOP_MODULE_fa_invoice_UserOrderEmailInvoiceFilenameFormat' => 'This setting is used to define the filename for the invoice attached to the Customer order confirmation email. <br><br><strong>Possible placeholders:</strong><br><br><strong>&lt;order:tableField&gt;</strong> format placeholder to include information from the oxorder table(eg. &lt;order:oxbillfname&gt; or &lt;order:oxbillnr&gt;)',
]);
