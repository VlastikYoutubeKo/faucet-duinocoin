<?php

namespace App\Models;

use App\Core\DB;
use PDO;

class Faucet
{
    private string $apiUrl = "https://server.duinocoin.com/";
    private int $cooldownTime;
    private string $walletUsername;
    private string $walletPassword;
    private string $memo;

    public function __construct()
    {
        $this->walletUsername = $_ENV['WALLET_USERNAME'] ?? "katfaucet";
        $this->walletPassword = $_ENV['WALLET_PASSWORD'] ?? "";
        $this->memo = $_ENV['MEMO'] ?? "KatFaucet";
        $this->cooldownTime = (int)($_ENV['COOLDOWN_TIME'] ?? 86400);
    }

    public function validateWalletAddress(string $walletAddress): bool
    {
        if (strlen($walletAddress) < 1) return false;
        if ($walletAddress === $this->walletUsername) return false;
        return preg_match('/^[a-zA-Z0-9_]{3,20}$/', $walletAddress);
    }

    public function checkWalletExists(string $recipient): bool
    {
        $url = $this->apiUrl . 'balances/' . $recipient;
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
        ]);

        $response = curl_exec($curl);
        curl_close($curl);
        $result = json_decode($response, true);
        
        return isset($result['success']) && $result['success'] === true;
    }

    public function isCooldown(string $recipient): bool
    {
        $db = DB::getInstance();
        $stmt = $db->prepare("SELECT timestamp FROM cooldown WHERE username = ?");
        $stmt->execute([$recipient]);
        $row = $stmt->fetch();
        
        if (!$row) return false;
        
        $currentTime = time();
        return ($currentTime - $row['timestamp'] < $this->cooldownTime);
    }

    public function getCooldownMessage(string $recipient): string
    {
        $db = DB::getInstance();
        $stmt = $db->prepare("SELECT timestamp FROM cooldown WHERE username = ?");
        $stmt->execute([$recipient]);
        $row = $stmt->fetch();
        
        if (!$row) return "No cooldown found";
        
        $currentTime = time();
        $remainingTime = $this->cooldownTime - ($currentTime - $row['timestamp']);
        $hours = floor($remainingTime / 3600);
        $minutes = floor(($remainingTime % 3600) / 60);
        $seconds = $remainingTime % 60;
        
        return "Be more Patient. Wait for $hours hours, $minutes minutes, and $seconds seconds before trying again. Find a Kat to pet while you wait.";
    }

    public function isBlacklisted(string $recipient): bool
    {
        $db = DB::getInstance();
        $stmt = $db->prepare("SELECT id FROM blacklist WHERE username = ?");
        $stmt->execute([$recipient]);
        
        return $stmt->fetch() !== false;
    }

    public function sendTransaction(string $recipient, string $ipAddress): array
    {
        $amount = mt_rand(1, 500) / 100;
        $currentTime = time();
        
        $params = [
            'username' => $this->walletUsername,
            'password' => $this->walletPassword,
            'recipient' => $recipient,
            'amount' => $amount,
            'memo' => $this->memo,
        ];

        $url = $this->apiUrl . 'transaction/?' . http_build_query($params);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $response = curl_exec($ch);
        curl_close($ch);

        $transactionData = json_decode($response, true);
        $success = isset($transactionData['success']) && $transactionData['success'];
        
        if ($success) {
            // Update cooldown
            $db = DB::getInstance();
            $stmt = $db->prepare("INSERT OR REPLACE INTO cooldown (username, timestamp, ip_address) VALUES (?, ?, ?)");
            $stmt->execute([$recipient, $currentTime, $ipAddress]);
            
            // Log transaction
            $txId = $transactionData['result']['id'] ?? null;
            $stmt = $db->prepare("INSERT INTO transactions 
                (recipient, amount, timestamp, ip_address, status, tx_id) 
                VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$recipient, $amount, $currentTime, $ipAddress, 'success', $txId]);
            
            return [
                'success' => true,
                'message' => "Transaction successful. Sent $amount DUCO to $recipient",
                'amount' => $amount
            ];
        } else {
            $error = $transactionData['error'] ?? 'Unknown error. Report this to kat on discord pls!';
            
            // Log failed transaction
            $db = DB::getInstance();
            $stmt = $db->prepare("INSERT INTO transactions 
                (recipient, amount, timestamp, ip_address, status) 
                VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$recipient, $amount, $currentTime, $ipAddress, 'failed']);
            
            return [
                'success' => false,
                'message' => "Error: Transaction failed. Reason: $error",
                'error' => $error
            ];
        }
    }

    public function getFaucetBalance(): float
    {
        $url = $this->apiUrl . 'balances/' . $this->walletUsername;
        
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        $result = json_decode($response, true);
        
        return $result['result']['balance'] ?? 0;
    }
    
    public function getTransactionHistory(int $limit = 10): array
    {
        $db = DB::getInstance();
        $stmt = $db->prepare("SELECT * FROM transactions 
                             WHERE status = 'success' 
                             ORDER BY timestamp DESC 
                             LIMIT ?");
        $stmt->execute([$limit]);
        
        return $stmt->fetchAll();
    }
    
    public function validateRecaptcha(string $recaptchaResponse): bool
    {
        $secretKey = $_ENV['RECAPTCHA_SECRET_KEY'] ?? '';
        
        if (empty($secretKey)) {
            return true; // Skip validation if not configured
        }
        
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $data = [
            'secret' => $secretKey,
            'response' => $recaptchaResponse,
            'remoteip' => $_SERVER['REMOTE_ADDR']
        ];
        
        $options = [
            'http' => [
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data)
            ]
        ];
        
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        $resultJson = json_decode($result, true);
        
        return $resultJson['success'] ?? false;
    }
}
