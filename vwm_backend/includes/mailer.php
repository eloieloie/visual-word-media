<?php
require_once __DIR__ . '/../config/mail.php';

/**
 * Send an email through the Mailgun HTTP API using cURL.
 *
 * @param string      $to      Recipient address (optionally "Name <addr>")
 * @param string      $subject Email subject
 * @param string      $html    HTML body
 * @param string|null $text    Plain-text fallback (auto-derived from $html if null)
 * @param array       $opts    Optional overrides: from, fromName, replyTo
 *
 * @return array{success: bool, message: string, status?: int, id?: string}
 */
function sendMail(string $to, string $subject, string $html, ?string $text = null, array $opts = []): array
{
    if (MAILGUN_DOMAIN === '' || MAILGUN_API_KEY === '') {
        return ['success' => false, 'message' => 'Mail is not configured (missing Mailgun domain or API key)'];
    }

    if (!function_exists('curl_init')) {
        return ['success' => false, 'message' => 'cURL is not available on this server'];
    }

    if ($text === null) {
        // Strip tags for a plain-text alternative
        $text = trim(html_entity_decode(strip_tags($html), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
    }

    $fromName    = $opts['fromName'] ?? MAIL_FROM_NAME;
    $fromAddress = $opts['from']     ?? MAIL_FROM_ADDRESS;
    $from        = sprintf('%s <%s>', $fromName, $fromAddress);

    $fields = [
        'from'    => $from,
        'to'      => $to,
        'subject' => $subject,
        'text'    => $text,
        'html'    => $html,
    ];
    if (!empty($opts['replyTo'])) {
        $fields['h:Reply-To'] = $opts['replyTo'];
    }

    $url = rtrim(MAILGUN_API_BASE, '/') . '/v3/' . rawurlencode(MAILGUN_DOMAIN) . '/messages';

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_USERPWD        => 'api:' . MAILGUN_API_KEY,
        CURLOPT_POSTFIELDS     => http_build_query($fields),
        CURLOPT_TIMEOUT        => 20,
        CURLOPT_HTTPHEADER     => ['Content-Type: application/x-www-form-urlencoded'],
    ]);

    $body   = curl_exec($ch);
    $errNo  = curl_errno($ch);
    $errMsg = curl_error($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($errNo) {
        return ['success' => false, 'message' => 'Mail transport error: ' . $errMsg];
    }

    $decoded = json_decode($body, true);

    if ($status >= 200 && $status < 300) {
        return [
            'success' => true,
            'message' => 'Email sent',
            'status'  => $status,
            'id'      => $decoded['id'] ?? null,
        ];
    }

    return [
        'success' => false,
        'message' => $decoded['message'] ?? ('Mailgun returned HTTP ' . $status),
        'status'  => $status,
    ];
}
