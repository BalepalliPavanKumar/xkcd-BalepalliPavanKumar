<?php require 'functions.php'; ?>
<!DOCTYPE html>
<html>
<head><title>Unsubscribe</title></head>
<body>
    <h2>Unsubscribe</h2>
    <form method="POST">
        <input type="email" name="unsubscribe_email" required>
        <button id="submit-unsubscribe">Unsubscribe</button>
    </form>

    <form method="POST">
        <input type="text" name="verification_code" maxlength="6" required>
        <button id="submit-verification">Verify</button>
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['unsubscribe_email'])) {
            $email = $_POST['unsubscribe_email'];
            $code = generateVerificationCode();
            $_SESSION['unsub_code'][$email] = $code;
            $subject = "Confirm Un-subscription";
            $message = "<p>To confirm un-subscription, use this code: <strong>$code</strong></p>";
            $headers = "MIME-Version: 1.0\r\nContent-type: text/html; charset=UTF-8\r\nFrom: no-reply@example.com\r\n";
            mail($email, $subject, $message, $headers);
            echo "<p>Verification code sent to $email</p>";
        } elseif (isset($_POST['verification_code'])) {
            $code = $_POST['verification_code'];
            $email = array_key_first($_SESSION['unsub_code']);
            if ($_SESSION['unsub_code'][$email] === $code) {
                unsubscribeEmail($email);
                echo "<p>Email unsubscribed successfully.</p>";
            } else {
                echo "<p>Invalid code.</p>";
            }
        }
    }
    ?>
</body>
</html>
