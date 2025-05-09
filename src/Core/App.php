<?php

namespace App\Core;

class App
{
    public static function run(): void
    {
        // Load environment variables
        self::loadEnvironment();
        
        // Start session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Initialize database
        DB::getInstance();
        
        // Initialize router
        $router = new Router();
        $router->dispatch();
    }
    
    private static function loadEnvironment(): void
    {
        // Check if .env file exists
        if (file_exists(dirname(__DIR__, 2) . '/.env')) {
            // Load environment variables from .env file
            $dotenv = \Dotenv\Dotenv::createImmutable(dirname(__DIR__, 2));
            $dotenv->load();
        } else {
            // Load some default values if .env doesn't exist
            $_ENV['WALLET_USERNAME'] = $_ENV['WALLET_USERNAME'] ?? 'katfaucet';
            $_ENV['APP_NAME'] = $_ENV['APP_NAME'] ?? 'KatFaucet';
            $_ENV['COOLDOWN_TIME'] = $_ENV['COOLDOWN_TIME'] ?? 86400;
            $_ENV['DB_TYPE'] = $_ENV['DB_TYPE'] ?? 'sqlite';
            $_ENV['DB_PATH'] = $_ENV['DB_PATH'] ?? 'storage/database/faucet.sqlite';
        }
    }
    
    public static function view(string $name, array $data = []): void
    {
        // Convert $data to variables
        extract($data);
        
        // Define path to view
        $viewPath = dirname(__DIR__) . "/Views/$name.php";
        
        // Check if view exists
        if (!file_exists($viewPath)) {
            http_response_code(404);
            echo "View not found: $name";
            return;
        }
        
        // Start output buffering
        ob_start();
        
        // Include view
        include $viewPath;
        
        // Get content and clean buffer
        $content = ob_get_clean();
        
        // Output content
        echo $content;
    }
    
    public static function csrf(): string
    {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        
        return $_SESSION['csrf_token'];
    }
    
    public static function validateCsrf(string $token): bool
    {
        return isset($_SESSION['csrf_token']) && $_SESSION['csrf_token'] === $token;
    }
    
    public static function sanitize($input)
    {
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }
    
    public static function redirect(string $url): void
    {
        header("Location: $url");
        exit;
    }
}
