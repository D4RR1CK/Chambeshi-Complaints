<?php
require_once "mail.php";

$sent = sendVerificationCode(
  "givennkonde535@gmail.com",
  "Test User",
  "123456"
);

echo $sent ? "✅ Email sent successfully!" : "❌ Email failed.";
