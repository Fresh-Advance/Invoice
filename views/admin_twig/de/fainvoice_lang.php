<?php

declare(strict_types=1);

$aLang = [];

require __DIR__ . "/../../../translations/de/module_de_lang.php";

$aLang = array_merge($aLang, [
    'FA_INVOICE_FORM_TITLE_NEW_DOCUMENT' => 'Neues Rechnungsdokument',
    'FA_INVOICE_FORM_TITLE_CURRENT_DOCUMENT' => 'Aktuelles Dokument',

    'FA_INVOICE_FORM_SIGNER' => 'In Rechnung gestellt von',
    'FA_INVOICE_FORM_NUMBER' => 'Rechnungsnummer',
    'FA_INVOICE_FORM_DATE' => 'Rechnungsdatum',
    'FA_INVOICE_FORM_SAVE_DATA' => 'Neues Rechnungsdokument generieren',
    'FA_INVOICE_FORM_CONFIRM_REGENERATE' => 'Möchten Sie das bereits vorhandene Dokument wirklich neu generieren?',

    'FA_INVOICE_FORM_DOWNLOAD' => 'Rechnung herunterladen',

    # Module settings
    'SHOP_MODULE_GROUP_fa_invoice_main' => 'Rechnungsdokument',
    'SHOP_MODULE_fa_invoice_FilenameFormat' => 'Dateiname für herunterladbare Rechnung im Admin-Panel',
    'HELP_SHOP_MODULE_fa_invoice_FilenameFormat' => 'Diese Einstellung wird verwendet, um den Dateinamen für die herunterladbare Rechnung im Admin-Panel zu definieren. <br><br><strong>Mögliche Platzhalter:</strong><br><br><strong>&lt;order:tableField&gt;</strong> Formatplatzhalter, um Informationen aus der oxorder-Tabelle einzuschließen (z. B. &lt;order:oxbillfname&gt;)<br><strong>&lt;invoiceNumber&gt;</strong> Platzhalter, um die Rechnungsnummer einzuschließen',
    'SHOP_MODULE_fa_invoice_IsForArchive' => 'Dokument ist für Archiv (PDFA)',
    'SHOP_MODULE_fa_invoice_InvoiceDateFormat' => 'Standard-Rechnungsdatumformat',

    # Module settings layout
    'SHOP_MODULE_GROUP_fa_invoice_layout' => 'Rechnungsdokumentlayout',
    'SHOP_MODULE_fa_invoice_MarginTop' => 'Rand oben (z.B. 10px)',
    'SHOP_MODULE_fa_invoice_MarginBottom' => 'Rand unten (z.B. 10px)',
    'SHOP_MODULE_fa_invoice_MarginLeft' => 'Rand links (z.B. 10px)',
    'SHOP_MODULE_fa_invoice_MarginRight' => 'Rand rechts (z.B. 10px)',
    'SHOP_MODULE_fa_invoice_DocumentHeader' => 'Dokumentenkopf (HTML mit einfachem Inline-CSS möglich)',
    'HELP_SHOP_MODULE_fa_invoice_DocumentHeader' => 'HTML mit einfachem Inline-CSS möglich. Es ist auch möglich, Bilder mit Base64-Kodierung einzubetten.',
    'SHOP_MODULE_fa_invoice_DocumentFooter' => 'Dokumentenfuß (HTML mit einfachem Inline-CSS möglich)',
    'HELP_SHOP_MODULE_fa_invoice_DocumentFooter' => 'HTML mit einfachem Inline-CSS möglich. Es ist auch möglich, Bilder mit Base64-Kodierung einzubetten.',

    # Module settings invoice numbering
    'SHOP_MODULE_GROUP_fa_invoice_numbering' => 'Rechnungsnummerierung',
    'SHOP_MODULE_fa_invoice_InvoiceNumberUpdate' => 'Bestellrechnungsnummer bei Rechnungserstellung aktualisieren',
    'SHOP_MODULE_fa_invoice_InvoiceNumberFormat' => 'Rechnungsnummerformat',
    'HELP_SHOP_MODULE_fa_invoice_InvoiceNumberFormat' => 'Das Rechnungsnummerformat ist dafür verantwortlich, Ihre Rechnungsnummer mit einer Serie zu dekorieren und die Nummer wie SE-ABC-123 zu erhalten, indem das Format SE-ABC-&lt;order:oxbillnr&gt;',

    # Module settings invoice mails
    'SHOP_MODULE_GROUP_fa_invoice_emails' => 'E-Mail-Optionen',
    'SHOP_MODULE_fa_invoice_SendInvoiceOnOwnerOrderEmail' => 'Rechnung generieren und an Bestellbestätigungs-E-Mail des Inhabers anhängen',
    'SHOP_MODULE_fa_invoice_OwnerOrderEmailInvoiceFilenameFormat' => 'Dateiname für an Bestellbestätigungs-E-Mail des Inhabers angehängte Rechnung',
    'HELP_SHOP_MODULE_fa_invoice_OwnerOrderEmailInvoiceFilenameFormat' => 'Diese Einstellung wird verwendet, um den Dateinamen für die an die Bestellbestätigungs-E-Mail des Inhabers angehängte Rechnung zu definieren. <br><br><strong>Mögliche Platzhalter:</strong><br><br><strong>&lt;order:tableField&gt;</strong> Formatplatzhalter, um Informationen aus der oxorder-Tabelle einzuschließen (z. B. &lt;order:oxbillfname&gt; oder &lt;order:oxbillnr&gt;)',
    'SHOP_MODULE_fa_invoice_SendInvoiceOnUserOrderEmail' => 'Rechnung generieren und an Bestellbestätigungs-E-Mail des Kunden anhängen',
    'SHOP_MODULE_fa_invoice_UserOrderEmailInvoiceFilenameFormat' => 'Dateiname für an Bestellbestätigungs-E-Mail des Kunden angehängte Rechnung',
    'HELP_SHOP_MODULE_fa_invoice_UserOrderEmailInvoiceFilenameFormat' => 'Diese Einstellung wird verwendet, um den Dateinamen für die an die Bestellbestätigungs-E-Mail des Kunden angehängte Rechnung zu definieren. <br><br><strong>Mögliche Platzhalter:</strong><br><br><strong>&lt;order:tableField&gt;</strong> Formatplatzhalter, um Informationen aus der oxorder-Tabelle einzuschließen (z. B. &lt;order:oxbillfname&gt; oder &lt;order:oxbillnr&gt;)',
]);
