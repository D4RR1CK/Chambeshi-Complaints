<?php
require_once "db.php";

$adminEmail = "givennkonde535@gmail.com";   // <-- put your admin email here
$newPassword = "12345678";          // <-- set the password you want

$newHash = password_hash($newPassword, PASSWORD_DEFAULT);

$sql = "UPDATE admins SET password_hash = ? WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $newHash, $adminEmail);
$stmt->execute();

if ($stmt->affected_rows > 0) {
  echo "Admin password updated for $adminEmail<br>";
  echo "New password is: <b>$newPassword</b><br>";
} else {
  echo " No admin found with email: $adminEmail<br>";
}
$stmt->close();
