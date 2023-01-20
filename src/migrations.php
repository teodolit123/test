<?php

use App\Database;

/**
 * Create DB tables, indexes & relations
 *
 * @return void
 */
function createTables(): void
{
    //TODO: totally refactor need, 1 change = 1 transaction migration

    /**
     * Tables' structure
     */
    $tablesStructures = [
        "CREATE TABLE IF NOT EXISTS `Currency` (
            `id` BIGINT UNSIGNED NOT NULL,
            `currency` CHAR(10) NOT NULL,
            `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` TIMESTAMP NOT NULL DEFAULT NOW() ON UPDATE NOW()
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

        "CREATE TABLE IF NOT EXISTS `Type` (
            `id` BIGINT UNSIGNED NOT NULL,
            `type` VARCHAR(64) NOT NULL,
            `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` TIMESTAMP NOT NULL DEFAULT NOW() ON UPDATE NOW()
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

         "CREATE TABLE IF NOT EXISTS `Items` (
            `id` BIGINT UNSIGNED NOT NULL,
            `name` VARCHAR(64) NOT NULL,
            `size` VARCHAR(64) NOT NULL,      
            `model` VARCHAR(64) NOT NULL,      
            `price` BIGINT UNSIGNED DEFAULT 0  NOT NULL,  
            `currencyId` BIGINT UNSIGNED DEFAULT 1 NOT NULL,
            `typeId` BIGINT UNSIGNED NOT NULL,
            `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` TIMESTAMP NOT NULL DEFAULT NOW() ON UPDATE NOW()
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;"
    ];

    /**
     * Indexes
     */
    $tablesIndexes = [
        "CREATE INDEX `name` ON `Items` (`name`);",
        "CREATE INDEX `size` ON `Items` (`size`);",
        "CREATE INDEX `price` ON `Items` (`price`);",
        "CREATE INDEX `model` ON `Items` (`model`);",
        "CREATE INDEX `typeId` ON `Items` (`typeId`);",
        "CREATE INDEX `currencyId` ON `Currency` (`id`);",
        "CREATE INDEX `typeId` ON `Type` (`id`);",
    ];

    /**
     * Foreign keys
     */
    $tablesForeignKeys = [
        "ALTER TABLE `Items` ADD CONSTRAINT `currencyId_fk` FOREIGN KEY  (`currencyId`) REFERENCES `Currency` (`id`);",
        "ALTER TABLE `Items` ADD CONSTRAINT `typeId_fk` FOREIGN KEY  (`typeId`) REFERENCES `Type` (`id`);"
    ];

    /**
     * Test migration
     */
    $testDates = [
        "INSERT INTO mvc_db.Currency (id, currency, created_at, updated_at) VALUES (1, '$', DEFAULT, DEFAULT)",
        "INSERT INTO mvc_db.Currency (id, currency, created_at, updated_at) VALUES (2, 'rub', DEFAULT, DEFAULT)",
        "INSERT INTO mvc_db.Type (id, type, created_at, updated_at) VALUES (1, 'shoes', DEFAULT, DEFAULT)",
        "INSERT INTO mvc_db.Type (id, type, created_at, updated_at) VALUES (2, 'jewelery', DEFAULT, DEFAULT)",
        "INSERT INTO mvc_db.Items (id, name, size, model, price, currencyId, typeId, created_at, updated_at) 
            VALUES (1, 'shoe1', '10', 'Model1', 100, 1, 1, DEFAULT, DEFAULT)",
        "INSERT INTO mvc_db.Items (id, name, size, model, price, currencyId, typeId, created_at, updated_at) 
            VALUES (2, 'shoe2', '20', 'Model1', 100, 1, 1, DEFAULT, DEFAULT)",
        "INSERT INTO mvc_db.Items (id, name, size, model, price, currencyId, typeId, created_at, updated_at) 
            VALUES (2, 'shoe3', '20', 'Model2', 200, 1, 1, DEFAULT, DEFAULT)",
        "INSERT INTO mvc_db.Items (id, name, size, model, price, currencyId, typeId, created_at, updated_at) 
            VALUES (3, 'jew1', '100', 'Model1', 500, 1, 2, DEFAULT, DEFAULT)",
        "INSERT INTO mvc_db.Items (id, name, size, model, price, currencyId, typeId, created_at, updated_at) 
            VALUES (3, 'jew2', '200', 'Model2' ,1000, 1, 2, DEFAULT, DEFAULT)",
        "INSERT INTO mvc_db.Items (id, name, size, model, price, currencyId, typeId, created_at, updated_at) 
            VALUES (3, 'jew3', '200', 'Model1', 50, 2, 2, DEFAULT, DEFAULT)",
    ];

    foreach ($tablesStructures as $tablesStructure) {
        Database::query($tablesStructure);
        Database::execute();
    }

    foreach ($tablesIndexes as $tablesIndex) {
        Database::query($tablesIndex);
        Database::execute();
    }

    foreach ($tablesForeignKeys as $tablesForeignKey) {
        Database::query($tablesForeignKey);
        Database::execute();
    }

    foreach ($testDates as $testData) {
        Database::query($testData);
        Database::execute();
    }
}
