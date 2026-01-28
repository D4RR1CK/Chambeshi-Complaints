<?php
// mail.php

// Manual PHPMailer include
require_once __DIR__ . "/PHPMailer/src/PHPMailer.php";
require_once __DIR__ . "/PHPMailer/src/SMTP.php";
require_once __DIR__ . "/PHPMailer/src/Exception.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * Sends a verification code to user's email (Gmail SMTP)
 */
function sendVerificationCode(string $toEmail, string $toName, string $code): bool
{
    // Load secrets from config file (DON'T COMMIT THIS FILE)
    $cfgPath = __DIR__ . "/config_mail.php";
    if (!file_exists($cfgPath)) {
        error_log("MAIL ERROR: config_mail.php not found.");
        return false;
    }

    $cfg = require $cfgPath;

    // Validate required config keys
    $required = ["SMTP_USER", "SMTP_PASS", "FROM_EMAIL", "FROM_NAME"];
    foreach ($required as $k) {
        if (empty($cfg[$k])) {
            error_log("MAIL ERROR: Missing config key: $k");
            return false;
        }
    }

    $mail = new PHPMailer(true);

    try {
        // SMTP settings (Gmail)
        $mail->isSMTP();
        $mail->Host       = $cfg["SMTP_HOST"] ?? "smtp.gmail.com";
        $mail->SMTPAuth   = true;
        $mail->Username   = $cfg["SMTP_USER"];
        $mail->Password   = $cfg["SMTP_PASS"];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = (int)($cfg["SMTP_PORT"] ?? 587);

        // Recommended
        $mail->CharSet = "UTF-8";

        // From + To
        $mail->setFrom($cfg["FROM_EMAIL"], $cfg["FROM_NAME"]);
        $mail->addAddress($toEmail, $toName);

        // Email content
        $safeName = htmlspecialchars($toName, ENT_QUOTES, "UTF-8");
        $safeCode = htmlspecialchars($code, ENT_QUOTES, "UTF-8");

        $mail->isHTML(true);
        $mail->Subject = "Verify your email - Upschool1";

        $mail->Body = "
            <div style='font-family: Arial, sans-serif; padding: 16px;'>
                <h2 style='margin: 0 0 12px;'>Email Verification</h2>
                <p style='margin: 0 0 8px;'>Hello <b>{$safeName}</b>,</p>
                <p style='margin: 0 0 8px;'>Your verification code is:</p>
                <div style='font-size: 30px; font-weight: 800; letter-spacing: 6px; color: #16a34a; margin: 12px 0;'>
                    {$safeCode}
                </div>
                <p style='margin: 0 0 8px;'>This code expires in <b>10 minutes</b>.</p>
                <p style='font-size: 12px; color: #64748b; margin-top: 18px;'>
                    If you did not request this, you can ignore this email.
                </p>
            </div>
        ";

        $mail->AltBody = "Hello {$toName}, your verification code is: {$code}. It expires in 10 minutes.";

        $mail->send();
        return true;

    } catch (Exception $e) {
        // View this in XAMPP Apache error log
        error_log("MAIL ERROR: " . $mail->ErrorInfo);
        return false;
    }
}
