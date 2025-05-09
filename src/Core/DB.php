<?php

namespace App\Core;

use PDO;
use PDOException;

class DB
{
    private static ?PDO $instance = null;

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            $dbType = $_ENV['DB_TYPE'] ?? 'sqlite';
            $dbPath = $_ENV['DB_PATH'] ?? 'storage/database/faucet.sqlite';
            
            try {
                if ($dbType === 'sqlite') {
                    self::$instance = new PDO("sqlite:" . $dbPath);
                } elseif ($dbType === 'mysql') {
                    // Add MySQL connection option if needed
                    $host = $_ENV['DB_HOST'] ?? 'localhost';
                    $name = $_ENV['DB_NAME'] ?? 'faucet';
                    $user = $_ENV['DB_USER'] ?? 'root';
                    $pass = $_ENV['DB_PASS'] ?? '';
                    self::$instance = new PDO("mysql:host=$host;dbname=$name", $user, $pass);
                }
                
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$instance->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                
                // Initialize tables if they don't exist
                self::initTables();
            } catch (PDOException $e) {
                error_log("Database connection error: " . $e->getMessage());
                die("Database connection failed. Check error log for details.");
            }
        }
        
        return self::$instance;
    }

    private static function initTables(): void
    {
        $db = self::$instance;
        
        // Create blacklist table
        $db->exec("CREATE TABLE IF NOT EXISTS blacklist (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT UNIQUE NOT NULL,
            reason TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");
        
        // Create cooldown table
        $db->exec("CREATE TABLE IF NOT EXISTS cooldown (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT UNIQUE NOT NULL,
            timestamp INTEGER NOT NULL,
            ip_address TEXT
        )");
        
        // Create transactions table
        $db->exec("CREATE TABLE IF NOT EXISTS transactions (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            recipient TEXT NOT NULL,
            amount REAL NOT NULL,
            timestamp INTEGER NOT NULL,
            ip_address TEXT,
            status TEXT DEFAULT 'success',
            tx_id TEXT
        )");
        
        // Migrate old data if it exists
        self::migrateOldData();
    }
    
    private static function migrateOldData(): void
    {
        // Migrate blacklist data
        $blacklistFile = 'data/blacklist.txt';
        if (file_exists($blacklistFile)) {
            $blacklist = file($blacklistFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            
            $db = self::getInstance();
            $stmt = $db->prepare("INSERT OR IGNORE INTO blacklist (username) VALUES (?)");
            
            foreach ($blacklist as $username) {
                $stmt->execute([$username]);
            }
        }
        
        // Migrate cooldown data
        $cooldownFile = 'data/cooldown.txt';
        if (file_exists($cooldownFile)) {
            $content = file_get_contents($cooldownFile);
            $cooldownData = json_decode($content, true) ?? [];
            
            $db = self::getInstance();
            $stmt = $db->prepare("INSERT OR IGNORE INTO cooldown (username, timestamp) VALUES (?, ?)");
            
            foreach ($cooldownData as $username => $timestamp) {
                $stmt->execute([$username, $timestamp]);
            }
        }
    }
}
