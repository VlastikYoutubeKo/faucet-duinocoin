<?php 
$content = '
<section class="hero">
    <h2>Get 0.01-5.00 Free Duinocoin Once a Day!</h2>
    <p>Enter your Duino-Coin username below to receive free DUCO</p>
</section>

<div class="faucet-container">
    <div class="faucet-box">
        <div class="faucet-stats">
            <div class="stat">
                <i class="fas fa-wallet"></i>
                <div>
                    <h3>Faucet Balance</h3>
                    <p id="faucet-balance">' . number_format($balance, 2) . ' DUCO</p>
                </div>
            </div>
            <div class="stat">
                <i class="fas fa-hand-holding-dollar"></i>
                <div>
                    <h3>Total Distributed</h3>
                    <p>' . number_format($totalDistributed, 2) . ' DUCO</p>
                </div>
            </div>
            <div class="stat">
                <i class="fas fa-list"></i>
                <div>
                    <h3>Total Claims</h3>
                    <p>' . number_format($transactionCount) . '</p>
                </div>
            </div>
        </div>

        <form id="claim-form" action="/claim" method="POST" onsubmit="return validateForm()">
            <label for="username"><i class="fas fa-user"></i> Enter your username:</label>
            <input type="text" id="username" name="username" placeholder="Your Duino-Coin username" required>
            <input type="hidden" name="csrf_token" value="' . $csrf . '">
            
            ' . (!empty($recaptchaSiteKey) ? '<div class="g-recaptcha" data-sitekey="' . $recaptchaSiteKey . '"></div>' : '') . '
            
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-coins"></i> Claim DUCO
            </button>
        </form>
    </div>
    
    <div class="mining-container">
        <h3><i class="fas fa-hammer"></i> Support the Faucet by Mining</h3>
        <div class="mining-buttons">
            <button id="loadIframeButton" class="btn btn-success">
                <i class="fas fa-play"></i> Start Mining
            </button>
            <button id="disableButton" class="btn btn-danger" disabled>
                <i class="fas fa-stop"></i> Stop Mining
            </button>
        </div>
        <div id="iframeContainer"></div>
    </div>
</div>

<section class="recent-transactions">
    <h3><i class="fas fa-history"></i> Recent Transactions</h3>
    
    <div class="transaction-list">
        <table>
            <thead>
                <tr>
                    <th>Recipient</th>
                    <th>Amount</th>
                    <th>Time</th>
                </tr>
            </thead>
            <tbody>';

if (!empty($recentTransactions)) {
    foreach ($recentTransactions as $tx) {
        $content .= '
                <tr>
                    <td>' . htmlspecialchars($tx['recipient']) . '</td>
                    <td>' . number_format($tx['amount'], 2) . ' DUCO</td>
                    <td>' . htmlspecialchars($tx['formatted_time']) . '</td>
                </tr>';
    }
} else {
    $content .= '
                <tr>
                    <td colspan="3">No transactions yet</td>
                </tr>';
}

$content .= '
            </tbody>
        </table>
    </div>
    
    <div class="view-all">
        <a href="/history" class="btn btn-secondary">
            <i class="fas fa-list"></i> View All Transactions
        </a>
    </div>
</section>';

include __DIR__ . '/layout.php';
