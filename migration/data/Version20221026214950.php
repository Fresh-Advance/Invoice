<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20221026214950 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create Invoices table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(
            "CREATE TABLE `fa_invoices` (
                    `order_id` char(32) NOT NULL,
                    `invoice_signer` varchar(255) DEFAULT '',
                    `invoice_date` varchar(100) DEFAULT '',
                    `invoice_number` varchar(50) DEFAULT '',
                    PRIMARY KEY (`order_id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8"
        );
    }

    public function down(Schema $schema): void
    {
        // There are no cases invoices table should be removed
    }
}
