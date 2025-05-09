<?php

namespace App\Controllers;

use App\Core\App;
use App\Models\Faucet;
use App\Models\Transaction;

class FaucetController
{
    private Faucet $faucet;
    private Transaction $transaction;
    
    public function __construct()
    {
        $this->faucet = new Faucet();
        $this->transaction = new Transaction();
    }
    
    public function index(): void
    {
        $balance = $this->faucet->getFaucetBalance();
        $csrf = App::csrf();
        $recentTransactions = $this->faucet->getTransactionHistory(5);
        $recaptchaSiteKey = $_ENV['RECAPTCHA_SITE_KEY'] ?? '';
        $appName = $_ENV['APP_NAME'] ?? 'KatFaucet';
        
        // Format transactions for display
        foreach ($recentTransactions as &$tx) {
            $tx['formatted_time'] = date('Y-m-d H:i:s', $tx['timestamp']);
        }
        
        // Get transaction stats
        $totalDistributed = $this->transaction->getTotalDistributed();
        $transactionCount = $this->transaction->getTransactionCount();
        
        App::view('home', [
            'balance' => $balance,
            'csrf' => $csrf,
            'recentTransactions' => $recentTransactions,
            'totalDistributed' => $totalDistributed,
            'transactionCount' => $transactionCount,
            'recaptchaSiteKey' => $recaptchaSiteKey,
            'appName' => $appName,
            'message' => $_SESSION['message'] ?? null
        ]);
        
        // Clear flash message
        unset($_SESSION['message']);
    }
    
    public function claim(): void
    {
        // Check if method is POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            $_SESSION['message'] = ['type' => 'error', 'text' => 'Method not allowed'];
            App::redirect('/');
            return;
        }
        
        // Validate CSRF token
        $csrf = App::sanitize($_POST['csrf_token'] ?? '');
        if (!App::validateCsrf($csrf)) {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'Invalid CSRF token'];
            App::redirect('/');
            return;
        }
        
        // Get and sanitize username
        $recipient = App::sanitize($_POST['username'] ?? '');
        
        // Validate reCAPTCHA if enabled
        $recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';
        $recaptchaEnabled = !empty($_ENV['RECAPTCHA_SECRET_KEY']);
        
        if ($recaptchaEnabled && !$this->faucet->validateRecaptcha($recaptchaResponse)) {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'Please complete the reCAPTCHA challenge'];
            App::redirect('/');
            return;
        }
        
        // Validate wallet address
        if (!$this->faucet->validateWalletAddress($recipient)) {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'Invalid wallet address'];
            App::redirect('/');
            return;
        }
        
        // Check if wallet exists
        if (!$this->faucet->checkWalletExists($recipient)) {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'Wallet doesn\'t exist'];
            App::redirect('/');
            return;
        }
        
        // Check cooldown
        if ($this->faucet->isCooldown($recipient)) {
            $_SESSION['message'] = ['type' => 'warning', 'text' => $this->faucet->getCooldownMessage($recipient)];
            App::redirect('/');
            return;
        }
        
        // Check blacklist
        if ($this->faucet->isBlacklisted($recipient)) {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'Sorry, you are not allowed to use this faucet. Go pet a kat instead.'];
            App::redirect('/');
            return;
        }
        
        // Send transaction
        $ipAddress = $_SERVER['REMOTE_ADDR'];
        $result = $this->faucet->sendTransaction($recipient, $ipAddress);
        
        if ($result['success']) {
            $_SESSION['message'] = ['type' => 'success', 'text' => $result['message']];
        } else {
            $_SESSION['message'] = ['type' => 'error', 'text' => $result['message']];
        }
        
        App::redirect('/');
    }
    
    public function getBalance(): void
    {
        // API endpoint to get current balance
        $balance = $this->faucet->getFaucetBalance();
        
        header('Content-Type: application/json');
        echo json_encode(['balance' => $balance]);
    }
