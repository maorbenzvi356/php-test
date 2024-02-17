<?php

declare(strict_types=1);

namespace App\Utils;

use App\Utils\DB;

/**
 * Factory for creating database connections.
 */
class ConnectionFactory implements ConnectionFactoryInterface {
    /**
     * Creates and returns a new DB connection
     *
     * @return \App\Utils\DB
     */
    public function createConnection(): DB {
        $dbName = $_ENV['DB_DATABASE'];
        $dbHost = $_ENV['DB_HOST'];
        $dbUser = $_ENV['DB_USERNAME'];
        $dbPassword = $_ENV['DB_PASSWORD'];
        $dsn = "mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4";

        return new DB($dsn, $dbUser, $dbPassword);
    }
}
