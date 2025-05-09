<?php

namespace App\Models;

use App\Core\DB;
use PDO;

class Transaction
{
    public function getRecentTransactions(int $limit = 10): array
    {
        $db = DB::getInstance();
        $stmt = $db->prepare("SELECT * FROM transactions 
                             WHERE status = 'success' 
                             ORDER BY timestamp DESC 
                             LIMIT ?");
        $stmt->execute([$limit]);
        
        return $stmt->fetchAll();
    }
    
    public function getTopRecipients(int $limit = 5): array
    {
        $db = DB::getInstance();
        $stmt = $db->query("SELECT recipient, 
                          COUNT(*) as transaction_count, 
                          SUM(amount) as total_amount 
                          FROM transactions 
                          WHERE status = 'success' 
                          GROUP BY recipient 
                          ORDER BY transaction_count DESC 
                          LIMIT $limit");
        
        return $stmt->fetchAll();
    }
    
    public function getTotalDistributed(): float
    {
        $db = DB::getInstance();
        $stmt = $db->query("SELECT SUM(amount) as total FROM transactions WHERE status = 'success'");
        $result = $stmt->fetch();
        
        return $result['total'] ?? 0;
    }
    
    public function getTransactionCount(): int
    {
        $db = DB::getInstance();
        $stmt = $db->query("SELECT COUNT(*) as count FROM transactions WHERE status = 'success'");
        $result = $stmt->fetch();
        
        return $result['count'] ?? 0;
    }
    
    public function formatTimestamp(int $timestamp): string
    {
        return date('Y-m-d H:i:s', $timestamp);
    }
}
