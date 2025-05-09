<?php
$content = '
<section class="history-page">
    <h2><i class="fas fa-history"></i> Transaction History</h2>
    
    <div class="stats-summary">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-coins"></i>
            </div>
            <div class="stat-info">
                <h3>Total Distributed</h3>
                <p>' . number_format($totalDistributed, 2) . ' DUCO</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-list"></i>
            </div>
            <div class="stat-info">
                <h3>Total Transactions</h3>
                <p>' . number_format($transactionCount) . '</p>
            </div>
        </div>
    </div>
    
    <div class="history-container">
        <div class="top-recipients">
            <h3><i class="fas fa-trophy"></i> Top Recipients</h3>
            <ul>';

if (!empty($topRecipients)) {
    foreach ($topRecipients as $recipient) {
        $content .= '
                <li>
                    <span class="username">' . htmlspecialchars($recipient['recipient']) . '</span>
                    <span class="amount">' . number_format($recipient['total_amount'], 2) . ' DUCO</span>
                    <span class="count">(' . $recipient['transaction_count'] . ' claims)</span>
                </li>';
    }
} else {
    $content .= '
                <li>No data available</li>';
}

$content .= '
            </ul>
        </div>
        
        <div class="transactions">
            <h3><i class="fas fa-exchange-alt"></i> All Transactions</h3>
            
            <table>
                <thead>
                    <tr>
                        <th>Recipient</th>
                        <th>Amount</th>
                        <th>Date & Time</th>
                        <th>TX ID</th>
                    </tr>
                </thead>
                <tbody>';

if (!empty($transactions)) {
    foreach ($transactions as $tx) {
        $content .= '
                    <tr>
                        <td>' . htmlspecialchars($tx['recipient']) . '</td>
                        <td>' . number_format($tx['amount'], 2) . ' DUCO</td>
                        <td>' . htmlspecialchars($tx['formatted_time']) . '</td>
                        <td>' . (!empty($tx['tx_id']) ? htmlspecialchars($tx['tx_id']) : 'N/A') . '</td>
                    </tr>';
    }
} else {
    $content .= '
                    <tr>
                        <td colspan="4">No transactions found</td>
                    </tr>';
}

$content .= '
                </tbody>
            </table>
            
            <div class="pagination">';

if ($totalPages > 1) {
    // Previous button
    if ($currentPage > 1) {
        $content .= '<a href="/history?page=' . ($currentPage - 1) . '" class="page-btn"><i class="fas fa-chevron-left"></i> Previous</a>';
    } else {
        $content .= '<span class="page-btn disabled"><i class="fas fa-chevron-left"></i> Previous</span>';
    }
    
    // Page numbers
    $startPage = max(1, $currentPage - 2);
    $endPage = min($totalPages, $currentPage + 2);
    
    if ($startPage > 1) {
        $content .= '<a href="/history?page=1" class="page-btn">1</a>';
        if ($startPage > 2) {
            $content .= '<span class="page-ellipsis">...</span>';
        }
    }
    
    for ($i = $startPage; $i <= $endPage; $i++) {
        if ($i == $currentPage) {
            $content .= '<span class="page-btn active">' . $i . '</span>';
        } else {
            $content .= '<a href="/history?page=' . $i . '" class="page-btn">' . $i . '</a>';
        }
    }
    
    if ($endPage < $totalPages) {
        if ($endPage < $totalPages - 1) {
            $content .= '<span class="page-ellipsis">...</span>';
        }
        $content .= '<a href="/history?page=' . $totalPages . '" class="page-btn">' . $totalPages . '</a>';
    }
    
    // Next button
    if ($currentPage < $totalPages) {
        $content .= '<a href="/history?page=' . ($currentPage + 1) . '" class="page-btn">Next <i class="fas fa-chevron-right"></i></a>';
    } else {
        $content .= '<span class="page-btn disabled">Next <i class="fas fa-chevron-right"></i></span>';
    }
}

$content .= '
            </div>
        </div>
    </div>
</section>';

include __DIR__ . '/layout.php';
