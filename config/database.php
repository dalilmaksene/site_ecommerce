<?php
if (!function_exists('getConnection')) {
    function getConnection(): PDO {
        global $pdo;
        if (isset($pdo) && $pdo instanceof PDO) {
            return $pdo;
        }

        $host = defined('DB_HOST') ? DB_HOST : '127.0.0.1';
        $dbname = defined('DB_NAME') ? DB_NAME : 'ecommerce';
        $user = defined('DB_USER') ? DB_USER : 'root';
        $pass = defined('DB_PASS') ? DB_PASS : '';
        $charset = defined('DB_CHARSET') ? DB_CHARSET : 'utf8mb4';

        $dsn = "mysql:host={$host};dbname={$dbname};charset={$charset}";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            $pdo = new PDO($dsn, $user, $pass, $options);
            return $pdo;
        } catch (PDOException $e) {
            error_log("DB connection error: " . $e->getMessage());
            throw $e;
        }
    }
}
?>