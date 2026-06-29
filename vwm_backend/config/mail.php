<?php
// ============================================================
//  Mailgun configuration
//  Prefer environment variables; the define() fallbacks let it
//  work on hosts where env vars aren't easy to set.
//  Set these on Hostinger (or in a non-committed file) — do NOT
//  commit the real API key to git.
// ============================================================

// Mailgun sending domain, e.g. mg.visualword.in
// NOTE: currently the Mailgun sandbox domain (test only — can send only to
// Authorized Recipients added in the Mailgun dashboard). Switch back to
// 'visualword.in' once that domain is verified and the plan is upgraded.
define('MAILGUN_DOMAIN', getenv('MAILGUN_DOMAIN') ?: 'sandbox2f1beecb363848a98cef5fa479eb61ca.mailgun.org');

// Mailgun private API key (starts with "key-" or the newer format)
// Keep empty fallback to avoid committing secrets.
define('MAILGUN_API_KEY', getenv('MAILGUN_API_KEY') ?: '');

// API base host. US region: api.mailgun.net  |  EU region: api.eu.mailgun.net
define('MAILGUN_API_BASE', getenv('MAILGUN_API_BASE') ?: 'https://api.mailgun.net');

// Default "From" header for outgoing mail
// Must be on MAILGUN_DOMAIN. For the sandbox domain, use postmaster@<sandbox>.
define('MAIL_FROM_ADDRESS', getenv('MAIL_FROM_ADDRESS') ?: 'postmaster@sandbox2f1beecb363848a98cef5fa479eb61ca.mailgun.org');
define('MAIL_FROM_NAME',    getenv('MAIL_FROM_NAME')    ?: 'Visual Word Media');

// Public frontend base URL, used to build links inside emails
// (no trailing slash). Hash routing means paths look like BASE/#/...
define('APP_FRONTEND_URL', getenv('APP_FRONTEND_URL') ?: 'https://visualword.in');
