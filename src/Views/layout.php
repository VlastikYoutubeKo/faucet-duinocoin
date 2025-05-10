<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $appName ?? 'KatFaucet' ?></title>
    <meta name="description" content="Get Free 0.01-5 free duinocoin once a day">
    <meta name="keywords" content="katfaucet, kat, Duinocoin, DUCO, Duinocoin faucet, crypto faucet, DUCO faucet, Free Duinocoin, Beginner crypto, Earn free crypto, Eco-Friendly crypto, Free crypto currency, Microcontroller Mining, Free, instant faucet">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/assets/img/favicon.ico">
    
    <!-- Styles -->
    <link rel="stylesheet" href="/assets/css/style.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <?php if(isset($recaptchaSiteKey) && !empty($recaptchaSiteKey)): ?>
    <!-- reCAPTCHA -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <?php endif; ?>
</head>
<body>
    <header>
        <div class="container">
            <h1><a href="/"><?= $appName ?? 'KatFaucet' ?> đź»</a></h1>
            <nav>
                <ul>
                    <li><a href="/" class="<?= $_SERVER['REQUEST_URI'] === '/' || $_SERVER['REQUEST_URI'] === '/home' ? 'active' : '' ?>">Home</a></li>
                    <li><a href="/history" class="<?= $_SERVER['REQUEST_URI'] === '/history' ? 'active' : '' ?>">History</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <div class="container">
            <?php if(isset($message) && is_array($message)): ?>
                <div class="alert alert-<?= $message['type'] ?>">
                    <?= $message['text'] ?>
                </div>
            <?php endif; ?>
            
            <?= $content ?? '' ?>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>If you want to donate, send DUCO to <strong>plainrock</strong>!</p>
	    <p>Source code: <a href="https://github.com/VlastikYoutubeKo/faucet-duinocoin" target="_blank"><i class="fab fa-github"></i> Here</a></p>
            <p class="copyright">Â© <?= date('Y') ?> <?= $appName ?? 'KatFaucet' ?></p>
        </div>
    </footer>
    
    <!-- Dark Mode Toggle -->
    <div class="theme-toggle">
        <button id="theme-toggle-btn">
            <i class="fas fa-moon"></i>
            <i class="fas fa-sun"></i>
        </button>
    </div>

    <!-- Scripts -->
    <script src="/assets/js/app.js"></script>
</body>
</html>
