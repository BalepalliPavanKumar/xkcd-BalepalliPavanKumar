<?php
session_start();

function generateVerificationCode() {
    return str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
}

function registerEmail($email) {
    $file = __DIR__ . '/registered_emails.txt';
    if (!in_array($email, file($file, FILE_IGNORE_NEW_LINES))) {
        file_put_contents($file, $email . PHP_EOL, FILE_APPEND);
    }
}

function unsubscribeEmail($email) {
    $file = __DIR__ . '/registered_emails.txt';
    $emails = file($file, FILE_IGNORE_NEW_LINES);
    $emails = array_filter($emails, fn($e) => trim($e) !== $email);
    file_put_contents($file, implode(PHP_EOL, $emails) . PHP_EOL);
}

function sendVerificationEmail($email, $code) {
    $subject = "Your Verification Code";
    $message = "<p>Your verification code is: <strong>$code</strong></p>";
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8\r\n";
    $headers .= "From: no-reply@example.com\r\n";
    mail($email, $subject, $message, $headers);
    $_SESSION['code'][$email] = $code;
}

function verifyCode($email, $code) {
    return isset($_SESSION['code'][$email]) && $_SESSION['code'][$email] === $code;
}

function fetchAndFormatXKCDData() {
    $id = rand(1, 2800); // Reasonable range
    $url = "https://xkcd.com/$id/info.0.json";
    $json = file_get_contents($url);
    $data = json_decode($json, true);
    return "<h2>XKCD Comic</h2>
            <img src=\"{$data['img']}\" alt=\"XKCD Comic\">
            <p><a href=\"#\" id=\"unsubscribe-button\">Unsubscribe</a></p>";
}

function sendXKCDUpdatesToSubscribers() {
    $file = __DIR__ . '/registered_emails.txt';
    $body = fetchAndFormatXKCDData();
    $subject = "Your XKCD Comic";
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8\r\n";
    $headers .= "From: no-reply@example.com\r\n";

    foreach (file($file, FILE_IGNORE_NEW_LINES) as $email) {
        $unsubscribeLink = "http://yourdomain.com/unsubscribe.php?email=" . urlencode($email);
        $finalBody = str_replace("#", $unsubscribeLink, $body);
        mail($email, $subject, $finalBody, $headers);
    }
}
