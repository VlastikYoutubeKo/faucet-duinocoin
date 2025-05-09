<?php
$appName = $_ENV['APP_NAME'] ?? 'KatFaucet';

$content = '
<section class="error-page">
    <div class="error-container">
        <div class="error-icon">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <h2>404 - Page Not Found</h2>
        <p>Sorry, the page you are looking for does not exist.</p>
        <a href="/" class="btn btn-primary">
            <i class="fas fa-home"></i> Go Home
        </a>
    </div>
</section>';

include __DIR__ . '/layout.php';
