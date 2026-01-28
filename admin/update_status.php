<?php
require_once "admin_auth.php";
requireAdmin();
require_once "../db.php";

// Just in case admin_auth.php doesn't start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$id = (int)($_POST["id"] ?? 0);
$status = trim($_POST["status"] ?? "");
$adminNote = trim($_POST["admin_note"] ?? "");
$resolutionMessage = trim($_POST["resolution_message"] ?? "");

$allowed = ["submitted", "in review", "in progress", "resolved"];

if ($id <= 0 || !in_array($status, $allowed, true)) {
    header("Location: dashboard.php");
    exit;
}

// Get current status first (so we log only if status changed)
$get = $conn->prepare("SELECT status FROM complaints WHERE id = ? LIMIT 1");
if (!$get) {
    header("Location: dashboard.php");
    exit;
}
$get->bind_param("i", $id);
$get->execute();
$res = $get->get_result();
$row = $res->fetch_assoc();
$get->close();

if (!$row) {
    header("Location: dashboard.php");
    exit;
}

$oldStatus = $row["status"];

// Update complaint status + notes
$sql = "UPDATE complaints
        SET status = ?, admin_note = ?, resolution_message = ?
        WHERE id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssi", $status, $adminNote, $resolutionMessage, $id);
$stmt->execute();
$stmt->close();

//  Log timeline only if status changed
if ($oldStatus !== $status) {
    // Build a timeline note (you can adjust this wording)
    $noteParts = [];

    if ($adminNote !== "") {
        $noteParts[] = "Admin note: " . $adminNote;
    } else {
        $noteParts[] = "Status changed to: " . $status;
    }

    if ($status === "resolved" && $resolutionMessage !== "") {
        $noteParts[] = "Resolution: " . $resolutionMessage;
    }

    $timelineNote = implode(" | ", $noteParts);

    // Insert into YOUR timeline table columns
    $t = $conn->prepare("INSERT INTO complaint_timeline (complaint_id, status, note) VALUES (?, ?, ?)");
    if ($t) {
        $t->bind_param("iss", $id, $status, $timelineNote);
        $t->execute();
        $t->close();
    }
}

header("Location: dashboard.php");
exit;
