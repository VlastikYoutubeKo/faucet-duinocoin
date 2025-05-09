<?php

namespace App\Core;

use App\Controllers\FaucetController;
use App\Controllers\HistoryController;

class Router
{
    public function dispatch(): void
    {
        $uri = $_SERVER['REQUEST_URI'];
        $uri = strtok($uri, '?');
        
        // Remove trailing slash
        $uri = rtrim($uri, '/');
        
        // Default to home if URI is empty
        if ($uri === '' || $uri === '/') {
            $uri = '/home';
        }
        
        // Route to appropriate controller based on URI
        switch ($uri) {
            case '/home':
                $controller = new FaucetController();
                $controller->index();
                break;
            
            case '/claim':
                $controller = new FaucetController();
                $controller->claim();
                break;
                
            case '/history':
                $controller = new HistoryController();
                $controller->index();
                break;
                
            case '/api/balance':
                $controller = new FaucetController();
                $controller->getBalance();
                break;
                
            default:
                http_response_code(404);
                App::view('404');
                break;
        }
    }
}
