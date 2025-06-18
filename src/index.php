<?php require 'functions.php'; ?>
<!DOCTYPE html>
<html>
<head><title>XKCD Subscription</title></head>
<body>
    <h2>Subscribe</h2>
    <form method="POST">
        <input type="email" name="email" required>
        <button id="submit-email">Submit</button>
    </form>

    <form method="POST">
        <input type="text" name="verification_code" maxlength="6" required>
        <button id="submit-verification">Verify</button>
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['email'])) {
            $email = $_POST['email'];
            $code = generateVerificationCode();
            sendVerificationEmail($email, $code);
            echo "<p>Verification code sent to $email</p>";
        } elseif (isset($_POST['verification_code'])) {
            $code = $_POST['verification_code'];
            $email = array_key_first($_SESSION['code']);
            if (verifyCode($email, $code)) {
                registerEmail($email);
                echo "<p>Email verified and subscribed!</p>";
            } else {
                echo "<p>Invalid verification code.</p>";
            }
        }
    }
    ?>
</body>
</html>
