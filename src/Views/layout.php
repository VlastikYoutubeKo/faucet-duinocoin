<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Základní nastavení -->
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Titulek -->
  <title><?= $appName ?? 'KatFaucet' ?> – Get Free DUCO Daily</title>

  <!-- Meta popis -->
  <meta name="description" 
        content="Claim 0.01–5 DUCO daily on <?= $appName ?? 'our DuinoCoin faucet' ?> – fast, secure, and eco‑friendly. Start earning free crypto now!">

  <!-- Meta keywords -->
  <meta name="keywords" 
        content="<?= $appName ?? 'KatFaucet' ?>, Duinocoin, DUCO, Duinocoin faucet, crypto faucet, DUCO faucet, Free Duinocoin, Beginner crypto, Earn free crypto, Eco-Friendly crypto, Free crypto currency, Microcontroller Mining, Free, instant faucet, DuinoCoin, DUCO, DuinoCoin faucet, free DUCO, crypto faucet, microcontroller mining, eco-friendly crypto, earn crypto, free cryptocurrency, duinocoin, duino coin, duino faucet, duko coin, mxnticek faucet, DUCO, crypto faucet, free duinocoin, duino coin price, duino coin claim, earn duco, duinocoin free, instant duino faucet, daily duino faucet, eco crypto, arduino crypto">

  <!-- Robots a kanonická URL -->
  <meta name="robots" content="index, follow">
  <link rel="canonical" href="https://<?= $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ?>">

  <!-- Open Graph -->
  <meta property="og:type" content="website">
  <meta property="og:url" content="https://<?= $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ?>">
  <meta property="og:title" content="<?= $appName ?? 'KatFaucet' ?> – Free DUCO Faucet">
  <meta property="og:description" content="Earn up to 5 DUCO daily on <?= $appName ?? 'our faucet' ?>. Instant, eco‑friendly, and easy to use.">
  <meta property="og:image" content="https://<?= $_SERVER['HTTP_HOST'] ?>/assets/img/babycaVOHEN_fauceta.png">

  <!-- Twitter Cards -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="<?= $appName ?? 'KatFaucet' ?> – Free DUCO Daily">
  <meta name="twitter:description" content="Claim up to 5 free DuinoCoin daily at <?= $appName ?>. No fees, no waiting. Start now.">
  <meta name="twitter:image" content="https://<?= $_SERVER['HTTP_HOST'] ?>/assets/img/babycaVOHEN_fauceta.png">

  <!-- JSON-LD strukturovaná data -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "WebSite",
    "url": "https://<?= $_SERVER['HTTP_HOST'] ?>",
    "name": "<?= $appName ?? 'KatFaucet' ?>",
    "description": "Claim free DUCO daily using <?= $appName ?? 'this faucet' ?> – fast, eco‑friendly and secure.",
    "publisher": {
      "@type": "Organization",
      "name": "<?= $appName ?? 'KatFaucet' ?>",
      "logo": {
        "@type": "ImageObject",
        "url": "https://<?= $_SERVER['HTTP_HOST'] ?>/assets/img/favicon.ico"
      }
    }
  }
  </script>

  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="/assets/img/favicon.ico">

  <!-- Styles a ikony -->
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

  <!-- Cloudflare Turnstile -->
  <?php if(isset($turnstileSiteKey) && !empty($turnstileSiteKey)): ?>
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
  <?php endif; ?>
</head>
<body>
    <header>
        <div class="container">
            <h1><a href="/"><?= $appName ?? 'KatFaucet' ?></a></h1>
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
            <div class="footer-content">
                <span>If you want to donate, send DUCO to <strong>plainrock</strong> • Redesigned using Claude.ai • </span>
                <span>Source: <a href="https://github.com/VlastikYoutubeKo/faucet-duinocoin" target="_blank"><i class="fab fa-github"></i> Here</a> • </span>
                <span class="copyright">© <?= date('Y') ?> <?= $appName ?? 'KatFaucet' ?></span>
            </div>
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
