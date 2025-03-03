<?php

declare(strict_types=1);

namespace App\Core\DB;

use PDO;

class DB
{
    private static PDO $pdo;

    public static function pdo(): PDO
    {
        if (!isset(self::$pdo)) {
            $config = [
                PDO::ATTR_PERSISTENT => true,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ];

            self::$pdo = new PDO("mysql:host=" . DB_HOST . "; dbname=" . DB_NAME . "; charset=" . DB_CHARSET . ";", DB_USER, DB_PASS, $config);
        }
        return self::$pdo;
    }
}
