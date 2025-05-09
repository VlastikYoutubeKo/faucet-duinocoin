<?php

namespace App\Controllers;

use App\Core\App;
use App\Models\Transaction;

class HistoryController
{
    private Transaction $transaction;
    
    public function __construct()
    {
        $this->transaction = new Transaction();
    }
    
    public function index(): void
    {
        // Get transactions with pagination
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $perPage = 20;
        $offset = ($page - 1) * $perPage;
        
        $db = \App\Core\DB::getInstance();
        $totalStmt = $db->query("SELECT COUNT(*) as count FROM transactions WHERE status = 'success'");
        $totalRows = $totalStmt->fetch()['count'];
        $totalPages = ceil($totalRows / $perPage);
        
        $stmt = $db->prepare("SELECT * FROM transactions 
                             WHERE status = 'success' 
                             ORDER BY timestamp DESC 
                             LIMIT ? OFFSET ?");
        $stmt->execute([$perPage, $offset]);
        $transactions = $stmt->fetchAll();
        
        // Format transactions for display
        foreach ($transactions as &$tx) {
            $tx['formatted_time'] = date('Y-m-d H:i:s', $tx['timestamp']);
        }
        
        // Get stats
        $totalDistributed = $this->transaction->getTotalDistributed();
        $transactionCount = $this->transaction->getTransactionCount();
        $topRecipients = $this->transaction->getTopRecipients();
        
        $appName = $_ENV['APP_NAME'] ?? 'KatFaucet';
        
        App::view('history', [
            'transactions' => $transactions,
            'totalDistributed' => $totalDistributed,
            'transactionCount' => $transactionCount,
            'topRecipients' => $topRecipients,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'appName' => $appName
        ]);
    }
}
