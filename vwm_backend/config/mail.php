<?php
// ============================================================
//  Mailgun configuration
//  Secrets (the API key) are loaded from config/secrets.php,
//  which is gitignored but still uploaded by deploy.ps1.
//  Environment variables, if set, take precedence over both.
//  Do NOT commit the real API key to git.
// ============================================================

// Load local secrets (gitignored). It may putenv() / define MAILGUN_API_KEY etc.
$__secrets = __DIR__ . '/secrets.php';
if (is_file($__secrets)) {
    require $__secrets;
}

// Mailgun sending domain (verified custom domain)
define('MAILGUN_DOMAIN', getenv('MAILGUN_DOMAIN') ?: 'mg.visualword.in');

// Mailgun private API key — provided via config/secrets.php or env var.
define('MAILGUN_API_KEY', getenv('MAILGUN_API_KEY') ?: '');

// API base host. US region: api.mailgun.net  |  EU region: api.eu.mailgun.net
define('MAILGUN_API_BASE', getenv('MAILGUN_API_BASE') ?: 'https://api.mailgun.net');

// Default "From" header for outgoing mail. Must be on MAILGUN_DOMAIN.
define('MAIL_FROM_ADDRESS', getenv('MAIL_FROM_ADDRESS') ?: 'no-reply@mg.visualword.in');
define('MAIL_FROM_NAME',    getenv('MAIL_FROM_NAME')    ?: 'Visual Word Media');

// Public frontend base URL, used to build links inside emails
// (no trailing slash). Hash routing means paths look like BASE/#/...
define('APP_FRONTEND_URL', getenv('APP_FRONTEND_URL') ?: 'https://visualword.in');
