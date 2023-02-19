<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

// This is acceptance bootstrap
use Webmozart\PathUtil\Path;

//require_once Path::join((new \OxidEsales\Facts\Facts())->getShopRootPath(), 'source', 'bootstrap.php');
require_once '/var/www/source/bootstrap.php';

$helper = new \OxidEsales\Codeception\Module\FixturesHelper();
$helper->loadRuntimeFixtures(__DIR__ . '/../_data/fixtures.php');
