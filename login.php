<?php
$pageTitle = "Login";
session_start();

require_once "db.php"; // ✅ use your shared db connection (recommended)

// Only allow POST requests (since login is from modal form)
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit;
}

/* ===================== INPUTS ===================== */
$email = trim($_POST["username"] ?? "");
$password = $_POST["password"] ?? "";

/* ===================== REDIRECT BACK ===================== */
/*
  return_to should be posted from the modal:
  <input type="hidden" name="return_to" value="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>">
*/
$returnTo = $_POST["return_to"] ?? ($_SERVER["HTTP_REFERER"] ?? "index.php");

// Basic safety: allow only local paths (avoid redirect attacks)
$parsed = parse_url($returnTo);
if (isset($parsed["host"]) || isset($parsed["scheme"])) {
    $returnTo = "index.php";
}
if ($returnTo === "") $returnTo = "index.php";

/* ===================== VALIDATION ===================== */
if ($email === "" || $password === "") {
    $_SESSION["login_error"] = "Please enter your email and password.";
    header("Location: " . $returnTo);
    exit;
}

/* ===================== FETCH USER ===================== */
$sql = "SELECT id, fullname, email, password_hash, is_verified
        FROM users
        WHERE email = ?
        LIMIT 1";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    $_SESSION["login_error"] = "Something went wrong. Please try again.";
    header("Location: " . $returnTo);
    exit;
}

$stmt->bind_param("s", $email);
$stmt->execute();
$res = $stmt->get_result();
$userRow = $res->fetch_assoc();
$stmt->close();

/* ===================== CHECK USER ===================== */
if (!$userRow) {
    $_SESSION["login_error"] = "No account found with that email.";
    header("Location: " . $returnTo);
    exit;
}

if (!password_verify($password, $userRow["password_hash"])) {
    $_SESSION["login_error"] = "Incorrect password. Please try again.";
    header("Location: " . $returnTo);
    exit;
}

/* ===================== VERIFIED CHECK ===================== */
if ((int)$userRow["is_verified"] !== 1) {
    $_SESSION["login_error"] = "Please verify your email before logging in.";
    header("Location: verify.php?email=" . urlencode($userRow["email"]));
    exit;
}

/* ===================== LOGIN SUCCESS ===================== */
$_SESSION["user_id"]  = $userRow["id"];
$_SESSION["fullname"] = $userRow["fullname"];
$_SESSION["email"]    = $userRow["email"];

$_SESSION["login_success"] = "Login successful!";
header("Location: " . $returnTo);
exit;
