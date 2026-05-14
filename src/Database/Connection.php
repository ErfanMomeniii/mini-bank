<?php

declare(strict_types=1);

namespace ErfanMomeniii\MiniBank\Database;

use PDO;

/**
 * Manages a singleton PostgreSQL PDO connection.
 *
 * Reads connection parameters from environment variables:
 * DB_HOST, DB_PORT, DB_NAME, DB_USER, DB_PASSWORD.
 *
 * Usage:
 *   $pdo = Connection::getInstance();
 */
class Connection
{
    private static ?PDO $instance = null;

    private function __construct()
    {
    }

    /**
     * Returns the shared PDO instance, creating it on first call.
     */
    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            $dsn = sprintf(
                'pgsql:host=%s;port=%s;dbname=%s',
                getenv('DB_HOST'),
                getenv('DB_PORT') ?: '5432',
                getenv('DB_NAME'),
            );

            self::$instance = new PDO(
                $dsn,
                getenv('DB_USER'),
                getenv('DB_PASSWORD'),
                [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]
            );
        }

        return self::$instance;
    }
}
