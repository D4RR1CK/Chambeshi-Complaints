<?php
$pageTitle = "Report an Issue";
include "header.php";
require_once "db.php";

// Must be logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$success = "";
$error = "";
$trackingId = "";

function generateTrackingId(): string {
    $year = date("Y");
    $rand = random_int(100000, 999999);
    return "CHMB-$year-$rand";
}

/**
 * Save multiple uploaded images from input name="photos[]"
 * Returns array of relative paths saved to disk.
 * If any file is invalid, it will be skipped (and recorded in $warnings).
 */
function saveMultipleUploadedImages(string $fieldName = "photos", array &$warnings = []): array {
    if (!isset($_FILES[$fieldName])) return [];

    $files = $_FILES[$fieldName];
    if (!is_array($files["name"])) return [];

    $allowedExt = ["jpg", "jpeg", "png", "gif", "webp"];
    $savedPaths = [];

    // Ensure upload folder exists
    $uploadDir = __DIR__ . "/uploads/complaints";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $count = count($files["name"]);

    for ($i = 0; $i < $count; $i++) {
        $name = $files["name"][$i] ?? "";
        $tmp  = $files["tmp_name"][$i] ?? "";
        $err  = $files["error"][$i] ?? UPLOAD_ERR_NO_FILE;

        if ($err === UPLOAD_ERR_NO_FILE) continue;

        if ($err !== UPLOAD_ERR_OK) {
            $warnings[] = "Failed to upload file: " . htmlspecialchars($name);
            continue;
        }

        // Basic size check (10MB)
        $size = (int)($files["size"][$i] ?? 0);
        if ($size > 10 * 1024 * 1024) {
            $warnings[] = "File too large (max 10MB): " . htmlspecialchars($name);
            continue;
        }

        // Extension check
        $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        if (!in_array($ext, $allowedExt, true)) {
            $warnings[] = "Invalid file type: " . htmlspecialchars($name);
            continue;
        }

        // Generate safe unique file name
        $newName = "complaint_" . time() . "_" . random_int(1000, 9999) . "_" . $i . "." . $ext;
        $dest = $uploadDir . "/" . $newName;

        if (move_uploaded_file($tmp, $dest)) {
            // store relative path in DB
            $savedPaths[] = "uploads/complaints/" . $newName;
        } else {
            $warnings[] = "Could not save: " . htmlspecialchars($name);
        }
    }

    return $savedPaths;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $roomNumber = trim($_POST["number"] ?? "");
    $issue = trim($_POST["issue"] ?? "");
    $previousIssue = ($_POST["previous_issue"] ?? "no");
    if ($previousIssue !== "yes" && $previousIssue !== "no") $previousIssue = "no";

    if ($issue === "") {
        $error = "Please describe your issue.";
    } else {
        $userId = (int)$_SESSION["user_id"];

        // Save multiple images (optional)
        $warnings = [];
        $photoPaths = saveMultipleUploadedImages("photos", $warnings);

        // Generate unique tracking id and insert complaint
        $tries = 0;
        $maxTries = 5;

        while ($tries < $maxTries) {
            $candidate = generateTrackingId();

            // NOTE: keep image_path column in complaints if your DB has it.
            // We'll store the FIRST photo in complaints.image_path for compatibility.
            $firstImage = $photoPaths[0] ?? null;

            $sql = "INSERT INTO complaints (tracking_id, user_id, room_number, issue, image_path, previous_issue)
                    VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);

            if (!$stmt) {
                $error = "Database error: failed to prepare statement.";
                break;
            }

            $stmt->bind_param("sissss", $candidate, $userId, $roomNumber, $issue, $firstImage, $previousIssue);

            if ($stmt->execute()) {
                $trackingId = $candidate;
                $success = "Issue submitted successfully!";

                // Get newly created complaint ID
                $complaintId = $conn->insert_id;

                // Insert photos into complaint_photos table
                if (!empty($photoPaths)) {
                    $p = $conn->prepare("INSERT INTO complaint_photos (complaint_id, file_path) VALUES (?, ?)");
                    if ($p) {
                        foreach ($photoPaths as $path) {
                            $p->bind_param("is", $complaintId, $path);
                            $p->execute();
                        }
                        $p->close();
                    }
                }

                // Insert first timeline event (Submitted)
                $status = "submitted";
                $note = "Complaint submitted by user";

                $t = $conn->prepare("INSERT INTO complaint_timeline (complaint_id, status, note) VALUES (?, ?, ?)");
                if ($t) {
                    $t->bind_param("iss", $complaintId, $status, $note);
                    $t->execute();
                    $t->close();
                }

                // If there were warnings (some files skipped), show them nicely
                if (!empty($warnings)) {
                    $success .= " (Some files were skipped: " . implode(" | ", $warnings) . ")";
                }

                $stmt->close();
                break;
            }

            // Duplicate tracking ID -> retry
            if ($conn->errno === 1062) {
                $tries++;
                $stmt->close();
                continue;
            } else {
                $error = "Database error: " . $conn->error;
                $stmt->close();
                break;
            }
        }

        if ($trackingId === "" && $error === "") {
            $error = "Could not generate a unique tracking ID. Please try again.";
        }
    }
}
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<section class="bg-gradient-to-br from-slate-50 to-blue-50 py-12 min-h-screen">
  <div class="max-w-4xl mx-auto px-4">

    <!-- Header -->
    <div class="text-center mb-10">
      <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gradient-to-br from-green-600 to-emerald-600 shadow-lg mb-4">
        <i class="fas fa-bullhorn text-white text-2xl"></i>
      </div>
      <h1 class="text-4xl font-bold bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent">
        Report an Issue
      </h1>
      <p class="text-slate-600 mt-3 text-lg">
        Submit your hostel complaint and get a tracking ID instantly
      </p>
    </div>

    <!-- Success Message -->
    <?php if ($success): ?>
      <div class="mb-8 bg-white rounded-2xl shadow-xl border-2 border-green-200 overflow-hidden">
        <div class="bg-gradient-to-r from-green-600 to-emerald-600 p-6">
          <div class="flex items-center gap-3 text-white">
            <i class="fas fa-check-circle text-3xl"></i>
            <div>
              <h3 class="text-xl font-bold">Success!</h3>
              <p class="text-green-50">Your issue has been submitted successfully</p>
            </div>
          </div>
        </div>
        <div class="p-6">
          <div class="mb-4">
            <p class="text-sm font-medium text-slate-600 mb-2">Your Tracking ID:</p>
            <div class="flex items-center gap-3 p-4 bg-slate-50 rounded-xl border-2 border-slate-200">
              <i class="fas fa-barcode text-green-600 text-2xl"></i>
              <span class="font-mono text-2xl font-bold text-slate-800 flex-1">
                <?php echo htmlspecialchars($trackingId); ?>
              </span>
              <button onclick="copyTrackingId(event, '<?php echo htmlspecialchars($trackingId); ?>')"
                      class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-all flex items-center gap-2">
                <i class="fas fa-copy"></i>
                Copy
              </button>
            </div>
          </div>
          <div class="flex items-start gap-3 p-4 bg-yellow-50 rounded-xl border border-yellow-200 mb-4">
            <i class="fas fa-exclamation-triangle text-yellow-600 text-xl mt-0.5"></i>
            <div class="flex-1">
              <p class="font-semibold text-yellow-900 text-sm">Important!</p>
              <p class="text-yellow-800 text-sm mt-1">
                Save this tracking ID to check your issue status.
              </p>
            </div>
          </div>
          <div class="flex gap-3">
            <a href="tracking.php" class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all shadow-lg hover:shadow-xl">
              <i class="fas fa-route"></i>
              Track This Issue
            </a>
            <a href="my_issues.php" class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-3 bg-slate-600 text-white font-semibold rounded-xl hover:bg-slate-700 transition-all shadow-lg hover:shadow-xl">
              <i class="fas fa-list"></i>
              View All Issues
            </a>
          </div>
        </div>
      </div>
    <?php endif; ?>

    <!-- Error Message -->
    <?php if ($error): ?>
      <div class="mb-8 p-5 rounded-xl bg-red-50 border-2 border-red-200 flex items-start gap-3">
        <i class="fas fa-exclamation-circle text-red-600 text-2xl mt-0.5"></i>
        <div class="flex-1">
          <p class="font-semibold text-red-800 text-lg">Error</p>
          <p class="text-red-700 mt-1"><?php echo htmlspecialchars($error); ?></p>
        </div>
      </div>
    <?php endif; ?>

    <!-- Form Card -->
    <div class="bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden">
      <div class="bg-gradient-to-r from-slate-50 to-blue-50 p-6 border-b border-slate-200">
        <div class="flex items-center gap-3">
          <i class="fas fa-edit text-blue-600 text-xl"></i>
          <h2 class="text-xl font-semibold text-slate-800">Issue Details</h2>
        </div>
        <p class="text-slate-600 text-sm mt-2">Please provide detailed information about your complaint</p>
      </div>

      <form action="" method="post" enctype="multipart/form-data" class="p-6 md:p-8 space-y-6">

        <!-- Room Number -->
        <div>
          <label class="block text-sm font-semibold text-slate-700 mb-2">
            <i class="fas fa-door-open text-slate-400 mr-2"></i>Room Number
          </label>
          <div class="relative">
            <input type="text" name="number"
                   value="<?php echo htmlspecialchars($_POST['number'] ?? ''); ?>"
                   placeholder="e.g. 55"
                   class="w-full pl-11 pr-4 py-3.5 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
            <i class="fas fa-hashtag absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
          </div>
          <p class="text-xs text-slate-500 mt-2">Enter your hostel room number</p>
        </div>

        <!-- Issue Description -->
        <div>
          <label class="block text-sm font-semibold text-slate-700 mb-2">
            <i class="fas fa-file-alt text-slate-400 mr-2"></i>What is the issue? <span class="text-red-600">*</span>
          </label>
          <div class="relative">
            <textarea name="issue" rows="4" required
                      placeholder="Please describe your issue in detail..."
                      class="w-full px-4 py-3.5 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all resize-none"><?php echo htmlspecialchars($_POST['issue'] ?? ''); ?></textarea>
          </div>
          <p class="text-xs text-slate-500 mt-2">Be specific about the problem you're experiencing</p>
        </div>

        <!-- ✅ MULTIPLE Image Upload -->
        <div>
          <label class="block text-sm font-semibold text-slate-700 mb-2">
            <i class="fas fa-image text-slate-400 mr-2"></i>Upload photos (optional)
          </label>

          <div class="relative border-2 border-dashed border-slate-300 rounded-xl p-6 hover:border-blue-400 transition-colors bg-slate-50">
            <input type="file"
                   name="photos[]"
                   accept="image/*"
                   multiple
                   id="photosInput"
                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                   onchange="updateFilesLabel(this)">

            <div class="text-center pointer-events-none">
              <i class="fas fa-cloud-upload-alt text-4xl text-slate-400 mb-3"></i>
              <p class="text-slate-700 font-semibold" id="filesDisplay">Click to upload or drag and drop</p>
              <p class="text-xs text-slate-500 mt-1">PNG, JPG, GIF, WEBP up to 10MB each</p>
            </div>
          </div>
        </div>

        <!-- Previous Issue -->
        <div>
          <label class="block text-sm font-semibold text-slate-700 mb-3">
            <i class="fas fa-history text-slate-400 mr-2"></i>Have you previously submitted this issue?
          </label>
          <div class="flex gap-4">
            <?php $prev = $_POST['previous_issue'] ?? ''; ?>
            <label class="flex-1 cursor-pointer">
              <input type="radio" name="previous_issue" value="yes" class="peer sr-only" <?php echo ($prev === 'yes') ? 'checked' : ''; ?>>
              <div class="p-4 border-2 border-slate-300 rounded-xl text-center peer-checked:border-blue-600 peer-checked:bg-blue-50 peer-checked:text-blue-700 transition-all hover:border-blue-400">
                <i class="fas fa-check-circle text-2xl mb-2"></i>
                <p class="font-semibold">YES</p>
              </div>
            </label>
            <label class="flex-1 cursor-pointer">
              <input type="radio" name="previous_issue" value="no" class="peer sr-only" <?php echo ($prev === 'no' || $prev === '') ? 'checked' : ''; ?>>
              <div class="p-4 border-2 border-slate-300 rounded-xl text-center peer-checked:border-blue-600 peer-checked:bg-blue-50 peer-checked:text-blue-700 transition-all hover:border-blue-400">
                <i class="fas fa-times-circle text-2xl mb-2"></i>
                <p class="font-semibold">NO</p>
              </div>
            </label>
          </div>
        </div>

        <!-- Submit Button -->
        <div class="pt-4">
          <button type="submit" class="w-full py-4 font-bold text-white bg-gradient-to-r from-green-600 to-emerald-600 rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 flex items-center justify-center gap-3 text-lg">
            <i class="fas fa-paper-plane"></i>
            Submit Issue
          </button>
        </div>
      </form>
    </div>

    <!-- Info Box -->
    <div class="mt-8 p-5 bg-blue-50 rounded-xl border border-blue-200">
      <div class="flex items-start gap-3">
        <i class="fas fa-info-circle text-blue-600 text-xl mt-1"></i>
        <div>
          <p class="font-semibold text-blue-900 mb-1">What happens next?</p>
          <ul class="text-blue-800 text-sm space-y-1">
            <li>• You'll receive a unique tracking ID immediately</li>
            <li>• Our team will review your complaint within 24 hours</li>
            <li>• You can track the status anytime using your tracking ID</li>
            <li>• We'll keep you updated on any progress</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
function updateFilesLabel(input) {
  const display = document.getElementById('filesDisplay');
  if (!display) return;

  if (!input.files || input.files.length === 0) {
    display.textContent = 'Click to upload or drag and drop';
    return;
  }

  if (input.files.length === 1) {
    display.textContent = input.files[0].name;
  } else {
    display.textContent = `${input.files.length} files selected`;
  }
}

function copyTrackingId(e, id) {
  e.preventDefault();
  navigator.clipboard.writeText(id).then(() => {
    const btn = e.target.closest('button');
    const originalHTML = btn.innerHTML;

    btn.innerHTML = '<i class="fas fa-check"></i> Copied!';
    btn.classList.add('bg-green-600');
    btn.classList.remove('bg-blue-600');

    setTimeout(() => {
      btn.innerHTML = originalHTML;
      btn.classList.remove('bg-green-600');
      btn.classList.add('bg-blue-600');
    }, 2000);
  });
}
</script>

<?php include "footer.php"; ?>