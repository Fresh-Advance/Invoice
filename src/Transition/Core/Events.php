<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Transition\Core;

use Exception;
use OxidEsales\DoctrineMigrationWrapper\MigrationsBuilder;
use Symfony\Component\Console\Output\BufferedOutput;

/**
 * Class defines what module does on Shop events.
 *
 * @codeCoverageIgnore
 */
final class Events
{
    /**
     * Execute action on activate event
     *
     * @throws Exception
     */
    public static function onActivate(): void
    {
        // execute module migrations
        self::executeModuleMigrations();
    }

    /**
     * Execute action on deactivate event
     *
     * @throws Exception
     */
    public static function onDeactivate(): void
    {
        //nothing to be done here for this module right now
    }

    /**
     * Execute necessary module migrations on activate event
     */
    private static function executeModuleMigrations(): void
    {
        $migrations = (new MigrationsBuilder())->build();

        $output = new BufferedOutput();
        $migrations->setOutput($output);
        $neeedsUpdate = $migrations->execute('migrations:up-to-date', 'fa_invoice');

        if ($neeedsUpdate) {
            $migrations->execute('migrations:migrate', 'fa_invoice');
        }
    }
}
