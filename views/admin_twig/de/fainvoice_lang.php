<?php

declare(strict_types=1);

$aLang = [];

require __DIR__ . "/../../../translations/de/module_de_lang.php";

$aLang = array_merge($aLang, [
    'FA_INVOICE_FORM_SIGNER' => 'In Rechnung gestellt von',
    'FA_INVOICE_FORM_NUMBER' => 'Rechnungsnummer',
    'FA_INVOICE_FORM_DATE' => 'Rechnungsdatum',
    'FA_INVOICE_FORM_SAVE_DATA' => 'Rechnungskonfiguration speichern',

    'FA_INVOICE_FORM_DOWNLOAD' => 'Rechnung herunterladen',

    # Module settings
    'SHOP_MODULE_GROUP_fa_invoice_main' => 'Rechnungsdokument',
    'SHOP_MODULE_fa_invoice_FilenamePrefix' => 'Dateiname Präfix',
    'SHOP_MODULE_fa_invoice_IsForArchive' => 'Dokument ist für Archiv (PDFA)',
    'SHOP_MODULE_fa_invoice_InvoiceDateFormat' => 'Standard-Rechnungsdatumformat',

    # Module settings layout
    'SHOP_MODULE_GROUP_fa_invoice_layout' => 'Rechnungsdokumentlayout',
    'SHOP_MODULE_fa_invoice_MarginTop' => 'Rand oben (z.B. 10px)',
    'SHOP_MODULE_fa_invoice_MarginBottom' => 'Rand unten (z.B. 10px)',
    'SHOP_MODULE_fa_invoice_MarginLeft' => 'Rand links (z.B. 10px)',
    'SHOP_MODULE_fa_invoice_MarginRight' => 'Rand rechts (z.B. 10px)',
    'SHOP_MODULE_fa_invoice_DocumentHeader' => 'Dokumentenkopf (HTML mit einfachem Inline-CSS möglich)',
    'SHOP_MODULE_fa_invoice_DocumentFooter' => 'Dokumentenfuß (HTML mit einfachem Inline-CSS möglich)',

    # Module settings invoice numbering
    'SHOP_MODULE_GROUP_fa_invoice_numbering' => 'Rechnungsnummerierung',
    'SHOP_MODULE_fa_invoice_InvoiceNumberUpdate' => 'Bestellrechnungsnummer bei Rechnungserstellung aktualisieren',
    'SHOP_MODULE_fa_invoice_InvoiceNumberFormat' => 'Rechnungsnummerformat',
]);
