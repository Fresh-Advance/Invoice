<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

/**
 * Metadata version
 */
$sMetadataVersion = '2.1';

/**
 * Module information
 */
$aModule = [
    'id' => 'fa_invoice',
    'title' => 'Invoice',
    'description' => [
        'en' => 'Invoice module for OXID eShop.',
    ],
    'thumbnail' => 'logo.png',
    'version' => '3.0.1',
    'author' => 'Anton Fedurtsya',
    'email' => 'anton@fedurtsya.com',
    'url' => 'https://github.com/Fresh-Advance',
    'controllers' => [
        'fa_invoice_admin' => \FreshAdvance\Invoice\Transition\Controller\Admin\InvoiceController::class,
    ],
    'extend' => [
        \OxidEsales\Eshop\Application\Model\OrderArticle::class => \FreshAdvance\Invoice\Transition\Model\OrderArticle::class,
        \OxidEsales\Eshop\Core\Language::class => \FreshAdvance\Invoice\Language\Extension\Language::class,
        \OxidEsales\Eshop\Core\Email::class => \FreshAdvance\Invoice\Transition\Core\Email::class,
    ],
    'settings' => [
        /** Main */
        [
            'group' => 'fa_invoice_main',
            'name' => \FreshAdvance\Invoice\Settings\ModuleSettings::SETTING_DOCUMENT_FILENAME_PREFIX,
            'type' => 'str',
            'value' => 'invoice-'
        ],
        [
            'group' => 'fa_invoice_main',
            'name' => \FreshAdvance\Invoice\Settings\ModuleSettings::SETTING_DOCUMENT_IS_FOR_ARCHIVE,
            'type' => 'bool',
            'value' => false
        ],
        [
            'group' => 'fa_invoice_main',
            'name' => \FreshAdvance\Invoice\Settings\ModuleSettings::SETTING_INVOICE_DATE_FORMAT,
            'type' => 'str',
            'value' => 'Y-m-d'
        ],

        /** Layout */
        [
            'group' => 'fa_invoice_layout',
            'name' => \FreshAdvance\Invoice\Document\Settings\DocumentLayoutSettings::SETTING_MARGIN_TOP,
            'type' => 'str',
            'value' => '',
        ],
        [
            'group' => 'fa_invoice_layout',
            'name' => \FreshAdvance\Invoice\Document\Settings\DocumentLayoutSettings::SETTING_MARGIN_BOTTOM,
            'type' => 'str',
            'value' => '',
        ],
        [
            'group' => 'fa_invoice_layout',
            'name' => \FreshAdvance\Invoice\Document\Settings\DocumentLayoutSettings::SETTING_MARGIN_LEFT,
            'type' => 'str',
            'value' => '',
        ],
        [
            'group' => 'fa_invoice_layout',
            'name' => \FreshAdvance\Invoice\Document\Settings\DocumentLayoutSettings::SETTING_MARGIN_RIGHT,
            'type' => 'str',
            'value' => '',
        ],
        [
            'group' => 'fa_invoice_layout',
            'name' => \FreshAdvance\Invoice\Document\Settings\DocumentLayoutSettings::SETTING_DOCUMENT_HEADER,
            'type' => 'str',
            'value' => '<small>Document Header Example; HTML with simple inline css can go here - Change in Module Settings</small>',
        ],
        [
            'group' => 'fa_invoice_layout',
            'name' => \FreshAdvance\Invoice\Document\Settings\DocumentLayoutSettings::SETTING_DOCUMENT_FOOTER,
            'type' => 'str',
            'value' => 'Document Footer Example; HTML with simple inline css can go here<br>Change in Module Settings',
        ],

        // group invoice numbering
        [
            'group' => 'fa_invoice_numbering',
            'name' => \FreshAdvance\Invoice\Order\Settings\OrderSettings::SETTING_INVOICE_NUMBER_UPDATE,
            'type' => 'bool',
            'value' => true
        ],
        [
            'group' => 'fa_invoice_numbering',
            'name' => \FreshAdvance\Invoice\Settings\ModuleSettings::SETTING_INVOICE_NUMBER_FORMAT,
            'type' => 'str',
            'value' => 'ABC-%1$s',
        ],

        // group emails
        [
            'group' => 'fa_invoice_emails',
            'name' => \FreshAdvance\Invoice\Settings\ModuleSettings::SETTING_SEND_INVOICE_ON_USER_ORDER_EMAIL,
            'type' => 'bool',
            'value' => false
        ],
    ],
    'events' => [
        'onActivate' => '\FreshAdvance\Invoice\Transition\Core\Events::onActivate',
        'onDeactivate' => '\FreshAdvance\Invoice\Transition\Core\Events::onDeactivate'
    ],
    'templates' => [
        '@fa_invoice/admin/invoice.tpl' => 'views/smarty/admin/invoice.tpl',
        '@fa_invoice/invoice/body.tpl' => 'views/smarty/invoice/body.tpl',
    ]
];
