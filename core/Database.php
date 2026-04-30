<?php
declare(strict_types=1);

namespace Core;

use PDO;
use PDOException;
use Exception;

/**
 * Database Class
 * Handles PDO connection and basic queries
 */
class Database
{
    private static ?PDO $instance = null;
    private static array $config = [];

    /**
     * Set database configuration
     * 
     * @param array $config [host, db_name, username, password]
     */
    public static function setConfig(array $config): void
    {
        self::$config = $config;
    }

    /**
     * Get PDO Instance (Singleton)
     * 
     * @return PDO
     * @throws Exception
     */
    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            if (empty(self::$config)) {
                throw new Exception("Database configuration not set.");
            }

            try {
                $dsn = sprintf(
                    "mysql:host=%s;dbname=%s;charset=utf8mb4",
                    self::$config['host'],
                    self::$config['db_name']
                );

                $options = [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ];

                self::$instance = new PDO(
                    $dsn,
                    self::$config['username'],
                    self::$config['password'],
                    $options
                );
            } catch (PDOException $e) {
                throw new Exception("Connection failed: " . $e->getMessage());
            }
        }

        return self::$instance;
    }

    /**
     * Run a safe query with prepared statements
     * 
     * @param string $sql
     * @param array $params
     * @return \PDOStatement
     */
    public static function query(string $sql, array $params = []): \PDOStatement
    {
        $stmt = self::getInstance()->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    /**
     * Fetch all records
     */
    public static function fetchAll(string $sql, array $params = []): array
    {
        return self::query($sql, $params)->fetchAll();
    }

    /**
     * Fetch a single record
     */
    public static function fetch(string $sql, array $params = []): array|false
    {
        return self::query($sql, $params)->fetch();
    }

    /**
     * Fetch a single column value
     */
    public static function fetchColumn(string $sql, array $params = []): mixed
    {
        return self::query($sql, $params)->fetchColumn();
    }

    /**
     * Insert and return last inserted ID
     */
    public static function insert(string $sql, array $params = []): string|false
    {
        self::query($sql, $params);
        return self::getInstance()->lastInsertId();
    }

    /**
     * Get a setting value by key
     */
    public static function getSetting(string $key, $default = null): mixed
    {
        $result = self::fetch("SELECT setting_value FROM settings WHERE setting_key = ?", [$key]);
        return $result ? $result['setting_value'] : $default;
    }

    /**
     * Update or Insert a setting
     */
    public static function updateSetting(string $key, string $value): bool
    {
        $exists = self::fetch("SELECT id FROM settings WHERE setting_key = ?", [$key]);
        if ($exists) {
            self::query("UPDATE settings SET setting_value = ? WHERE setting_key = ?", [$value, $key]);
        } else {
            self::query("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?)", [$key, $value]);
        }
        return true;
    }

    /**
     * Begin a transaction
     */
    public static function beginTransaction(): bool
    {
        return self::getInstance()->beginTransaction();
    }

    /**
     * Commit a transaction
     */
    public static function commit(): bool
    {
        return self::getInstance()->commit();
    }

    /**
     * Roll back a transaction
     */
    public static function rollBack(): bool
    {
        return self::getInstance()->rollBack();
    }
}
