<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

/**
 * Metadata version
 */
$sMetadataVersion = '2.1';

/**
 * Module information
 */
$aModule = [
    'id'          => 'fa_invoice',
    'title'       => 'Invoice',
    'description'  => [
        'en' => 'Invoice module for OXID eShop.',
    ],
    'version'     => '0.1.0',
    'author'       => 'Anton Fedurtsya',
    'email'        => 'anton@fedurtsya.com',
    'url'         => '',
    'controllers' => [
        'fa_invoice' => \FreshAdvance\Invoice\Transition\Controller\Admin\InvoiceController::class
    ],
    'extend'      => [
    ],
    'settings' => [
    ],
    'templates' => [
        '@fa_invoice/admin/invoice.tpl' => 'views/smarty/admin/invoice.tpl',
        '@fa_invoice/invoice/body.tpl' => 'views/smarty/invoice/body.tpl',
    ]
];