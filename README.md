# DuinoCoin PHP Faucet (Modern Edition)

This is a modernized PHP-based DuinoCoin faucet, originally developed by [Gamecat999](https://github.com/Gamecat999/Php-Duinocoin-Faucet).  
You can find the original version here: [github.com/Gamecat999/Php-Duinocoin-Faucet](https://github.com/Gamecat999/Php-Duinocoin-Faucet)  
Live demo of the old faucet: [https://katfaucet.com](https://katfaucet.com)

Live version of this code: [tokeny.odjezdy.online](https://tokeny.odjezdy.online) & [faucet.mxnticek.is-not-a.dev](https://faucet.mxnticek.is-not-a.dev)

## What's New

This version is a complete refresh of the original faucet, featuring:

- **Redesigned UI** for a cleaner and more modern experience
- **Refactored PHP backend**, making the codebase easier to maintain and extend
- **Cloudflare Turnstile** support instead of Google reCAPTCHA
- **Blacklist system** to block abusive or bot addresses
- **Self-hostable** – deploy on your own server or VPS (free hosts not recommended)

## Getting Started

1. Clone this repository to your server.
2. Configure the faucet settings and Turnstile site/secret keys.
3. Upload to a PHP-compatible hosting environment.
4. Done – your faucet is now online!

## Notes

- You **must** register for [Cloudflare Turnstile](https://www.cloudflare.com/products/turnstile/) if you intend to use anti-bot protection.
- You can disable Turnstile in the config if not required.
- No support for free hosting providers — use VPS or paid shared hosting for best results.

## Community

Questions? Suggestions?  
Join the discussion on Discord: [https://discord.gg/HUbHqUQUD2](https://discord.gg/HUbHqUQUD2)

---

![DuinoCoin Faucet Screenshot](https://github.com/user-attachments/assets/4d6c4a3b-0a69-4509-82ee-a58d85c03859)