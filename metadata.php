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
    'version' => '2.2.0',
    'author' => 'Anton Fedurtsya',
    'email' => 'anton@fedurtsya.com',
    'url' => 'https://github.com/Fresh-Advance',
    'controllers' => [
        'fa_invoice_admin' => \FreshAdvance\Invoice\Transition\Controller\Admin\InvoiceController::class,
    ],
    'extend' => [
        \OxidEsales\Eshop\Application\Model\OrderArticle::class => \FreshAdvance\Invoice\Transition\Model\OrderArticle::class,
        \OxidEsales\Eshop\Core\Language::class => \FreshAdvance\Invoice\Language\Extension\Language::class
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

        /** Layout */
        [
            'group' => 'fa_invoice_layout',
            'name' => \FreshAdvance\Invoice\Settings\Service\DocumentLayoutSettingsService::SETTING_MARGIN_TOP,
            'type' => 'str',
            'value' => '',
        ],
        [
            'group' => 'fa_invoice_layout',
            'name' => \FreshAdvance\Invoice\Settings\Service\DocumentLayoutSettingsService::SETTING_MARGIN_BOTTOM,
            'type' => 'str',
            'value' => '',
        ],
        [
            'group' => 'fa_invoice_layout',
            'name' => \FreshAdvance\Invoice\Settings\Service\DocumentLayoutSettingsService::SETTING_MARGIN_LEFT,
            'type' => 'str',
            'value' => '',
        ],
        [
            'group' => 'fa_invoice_layout',
            'name' => \FreshAdvance\Invoice\Settings\Service\DocumentLayoutSettingsService::SETTING_MARGIN_RIGHT,
            'type' => 'str',
            'value' => '',
        ],
        [
            'group' => 'fa_invoice_layout',
            'name' => \FreshAdvance\Invoice\Settings\Service\DocumentLayoutSettingsService::SETTING_DOCUMENT_HEADER,
            'type' => 'str',
            'value' => 'Document Header Example; HTML with simple inline css can go here<br>Change in Module Settings',
        ],
        [
            'group' => 'fa_invoice_layout',
            'name' => \FreshAdvance\Invoice\Settings\Service\DocumentLayoutSettingsService::SETTING_DOCUMENT_FOOTER,
            'type' => 'str',
            'value' => 'Document Footer Example; HTML with simple inline css can go here<br>Change in Module Settings',
        ]
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
