<?php

/**
 * Generate a 6-digit numeric verification code.
 */
function generateVerificationCode(): string {
    // TODO: Implement this function
    return str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
}

/**
 * Send a verification code to an email.
 */
function sendVerificationEmail(string $email, string $code): bool {
    // TODO: Implement this function
    $subject = "Your Verification Code";
    $message = "<p>Your verification code is: <strong>$code</strong></p>";
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8\r\n";
    $headers .= "From: no-reply@example.com\r\n";
    mail($email, $subject, $message, $headers);
}

/**
 * Register an email by storing it in a file.
 */
function registerEmail(string $email): bool {
     $file = __DIR__ . '/registered_emails.txt';
    $emails = file_exists($file) ? file($file, FILE_IGNORE_NEW_LINES) : [];
    if (!in_array($email, $emails)) {
        file_put_contents($file, $email . PHP_EOL, FILE_APPEND);
    }
    // TODO: Implement this function
}

/**
 * Unsubscribe an email by removing it from the list.
 */
function unsubscribeEmail(string $email): bool {
    $file = __DIR__ . '/registered_emails.txt';
    $emails = file($file, FILE_IGNORE_NEW_LINES);
    $emails = array_filter($emails, fn($e) => $e !== $email);
    file_put_contents($file, implode(PHP_EOL, $emails) . PHP_EOL);
    // TODO: Implement this function
}

/**
 * Fetch random XKCD comic and format data as HTML.
 */
function fetchAndFormatXKCDData(): string {
    // TODO: Implement this function
    $id = random_int(1, 3000);
    $url = "https://xkcd.com/$id/info.0.json";
    $json = file_get_contents($url);
    $data = json_decode($json, true);
    return "<h2>XKCD Comic</h2><img src=\"{$data['img']}\" alt=\"XKCD Comic\"><p><a href=\"#\" id=\"unsubscribe-button\">Unsubscribe</a></p>";
}

/**
 * Send the formatted XKCD updates to registered emails.
 */
function sendXKCDUpdatesToSubscribers(): void {
   $emails = file(__DIR__ . '/registered_emails.txt', FILE_IGNORE_NEW_LINES);
    $body = fetchAndFormatXKCDData();
    foreach ($emails as $email) {
        $subject = "Your XKCD Comic";
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8\r\n";
        $headers .= "From: no-reply@example.com\r\n";
        mail($email, $subject, $body, $headers);
    }
    
    // TODO: Implement this function
}
