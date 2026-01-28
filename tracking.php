<?php
$pageTitle = "Issue Tracking";
include "header.php";
require_once "db.php";

// Must be logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$userId = (int)$_SESSION["user_id"];

$trackingId = "";
$error = "";
$complaint = null;
$timeline = [];
$photos = [];

/* ===================== PHOTO URL HELPER ===================== */
function photoUrl(string $path): string {
    $path = trim($path);
    if ($path === "") return "";
    if (preg_match('#^https?://#i', $path)) return $path; // keep absolute urls
    return ltrim($path, "/"); // tracking.php is in root, so just use relative
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $trackingId = trim($_POST["tracking_id"] ?? "");

    if ($trackingId === "") {
        $error = "Please enter your Tracking ID.";
    } else {
        // Complaint (user can only fetch their own)
        $sql = "SELECT id, tracking_id, room_number, issue, previous_issue, status, created_at, updated_at,
                       admin_note, resolution_message
                FROM complaints
                WHERE tracking_id = ? AND user_id = ?
                LIMIT 1";

        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            $error = "Database error: failed to prepare statement.";
        } else {
            $stmt->bind_param("si", $trackingId, $userId);
            $stmt->execute();
            $res = $stmt->get_result();
            $complaint = $res->fetch_assoc();
            $stmt->close();

            if (!$complaint) {
                $error = "No issue found for that Tracking ID in your account.";
            } else {
                $cid = (int)$complaint["id"];

                // Timeline
                $t = $conn->prepare("SELECT status, note, created_at
                                     FROM complaint_timeline
                                     WHERE complaint_id = ?
                                     ORDER BY created_at ASC");
                if ($t) {
                    $t->bind_param("i", $cid);
                    $t->execute();
                    $timeline = $t->get_result()->fetch_all(MYSQLI_ASSOC);
                    $t->close();
                }

                // ✅ MULTI PHOTOS
                $p = $conn->prepare("SELECT file_path
                                     FROM complaint_photos
                                     WHERE complaint_id = ?
                                     ORDER BY id ASC");
                if ($p) {
                    $p->bind_param("i", $cid);
                    $p->execute();
                    $photos = $p->get_result()->fetch_all(MYSQLI_ASSOC);
                    $p->close();
                }
            }
        }
    }
}

// Status configuration
$statusConfig = [
    "submitted"   => ["icon" => "fa-inbox",        "color" => "blue",   "label" => "Submitted"],
    "in review"   => ["icon" => "fa-search",       "color" => "yellow", "label" => "In Review"],
    "in progress" => ["icon" => "fa-spinner",      "color" => "orange", "label" => "In Progress"],
    "resolved"    => ["icon" => "fa-check-circle", "color" => "green",  "label" => "Resolved"]
];

function statusMeta($status, $statusConfig) {
    if (isset($statusConfig[$status])) return $statusConfig[$status];
    return ["icon" => "fa-circle-question", "color" => "gray", "label" => ucwords($status)];
}
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<section class="bg-gradient-to-br from-slate-50 to-blue-50 py-12 min-h-screen">
  <div class="max-w-4xl mx-auto px-4">

    <!-- Header -->
    <div class="text-center mb-10">
      <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gradient-to-br from-blue-600 to-indigo-600 shadow-lg mb-4">
        <i class="fas fa-route text-white text-2xl"></i>
      </div>
      <h1 class="text-4xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
        Issue Tracking
      </h1>
      <p class="text-slate-600 mt-3 text-lg">
        Track your hostel complaint from submission to resolution
      </p>
    </div>

    <!-- Search Card -->
    <div class="bg-white rounded-2xl shadow-xl border border-slate-200 p-6 md:p-8 mb-6">
      <div class="flex items-center gap-3 mb-6">
        <i class="fas fa-search text-blue-600 text-xl"></i>
        <h2 class="text-xl font-semibold text-slate-800">Enter Tracking ID</h2>
      </div>

      <form method="POST" class="flex flex-col md:flex-row gap-3">
        <div class="flex-1 relative">
          <i class="fas fa-barcode absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
          <input
            type="text"
            name="tracking_id"
            value="<?php echo htmlspecialchars($trackingId); ?>"
            placeholder="Enter your Tracking ID (e.g. CHMB-2026-123456)"
            class="w-full pl-12 pr-4 py-3.5 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
            required
          />
        </div>
        <button
          type="submit"
          class="px-8 py-3.5 font-bold text-white bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all shadow-lg hover:shadow-xl flex items-center justify-center gap-2"
        >
          <i class="fas fa-search"></i>
          Track Issue
        </button>
      </form>

      <?php if ($error): ?>
        <div class="mt-6 p-4 rounded-xl bg-red-50 border border-red-200 flex items-start gap-3">
          <i class="fas fa-exclamation-circle text-red-600 text-xl mt-0.5"></i>
          <div class="flex-1">
            <p class="font-semibold text-red-800">Error</p>
            <p class="text-red-700 mt-1"><?php echo htmlspecialchars($error); ?></p>
          </div>
        </div>
      <?php endif; ?>
    </div>

    <?php if ($complaint):
      $status = $complaint["status"];
      $statusInfo = $statusConfig[$status] ?? ["icon" => "fa-question", "color" => "gray", "label" => $status];
    ?>

      <!-- Results Card -->
      <div class="bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden">

        <!-- Header with Status -->
        <div class="bg-gradient-to-r from-<?php echo $statusInfo['color']; ?>-50 to-<?php echo $statusInfo['color']; ?>-100 border-b border-<?php echo $statusInfo['color']; ?>-200 p-6">
          <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
              <div class="flex items-center gap-2 mb-2">
                <i class="fas fa-ticket-alt text-slate-600"></i>
                <span class="text-sm font-medium text-slate-600">Tracking ID</span>
              </div>
              <div class="font-mono text-2xl font-bold text-slate-800">
                <?php echo htmlspecialchars($complaint["tracking_id"]); ?>
              </div>
            </div>

            <div class="inline-flex items-center gap-2 px-5 py-3 bg-<?php echo $statusInfo['color']; ?>-100 border-2 border-<?php echo $statusInfo['color']; ?>-300 rounded-xl">
              <i class="fas <?php echo $statusInfo['icon']; ?> text-<?php echo $statusInfo['color']; ?>-700 text-xl"></i>
              <span class="font-bold text-<?php echo $statusInfo['color']; ?>-800 text-lg">
                <?php echo htmlspecialchars(ucwords($status)); ?>
              </span>
            </div>
          </div>
        </div>

        <!-- Details -->
        <div class="p-6">

          <!-- Complaint Details Grid -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div class="p-5 bg-slate-50 rounded-xl border border-slate-200">
              <p class="text-sm font-medium text-slate-600"><i class="fas fa-door-open mr-2 text-slate-500"></i>Room Number</p>
              <p class="text-xl font-bold text-slate-800 mt-2"><?php echo htmlspecialchars($complaint["room_number"] ?: "N/A"); ?></p>
            </div>

            <div class="p-5 bg-slate-50 rounded-xl border border-slate-200">
              <p class="text-sm font-medium text-slate-600"><i class="fas fa-history mr-2 text-slate-500"></i>Previously Submitted?</p>
              <p class="text-xl font-bold text-slate-800 mt-2"><?php echo htmlspecialchars(ucfirst($complaint["previous_issue"])); ?></p>
            </div>
          </div>

          <!-- Issue Description -->
          <div class="p-5 bg-blue-50 rounded-xl border border-blue-200">
            <p class="text-sm font-semibold text-slate-700 mb-2">
              <i class="fas fa-file-alt text-blue-600 mr-2"></i>Issue Description
            </p>
            <p class="text-slate-800 leading-relaxed"><?php echo nl2br(htmlspecialchars($complaint["issue"])); ?></p>
          </div>

          <!-- ✅ PHOTO GALLERY -->
          <div class="mt-6 p-5 bg-slate-50 rounded-xl border border-slate-200">
            <div class="flex items-center justify-between mb-3">
              <p class="text-sm font-semibold text-slate-700">
                <i class="fas fa-image text-slate-600 mr-2"></i>Uploaded Photos
              </p>
              <span class="text-xs font-bold text-slate-600 bg-white border border-slate-200 px-2 py-1 rounded-full">
                <?php echo count($photos); ?>
              </span>
            </div>

            <?php if (empty($photos)): ?>
              <p class="text-sm text-slate-600">No photos uploaded.</p>
            <?php else: ?>
              <div class="flex flex-wrap gap-3">
                <?php foreach ($photos as $ph):
                  $url = photoUrl($ph["file_path"]);
                  $safe = htmlspecialchars($url);
                ?>
                  <button type="button"
                          class="w-24 h-24 rounded-xl overflow-hidden border border-slate-200 bg-white shadow-sm hover:shadow-md transition"
                          onclick="openImageModal('<?php echo $safe; ?>')">
                    <img src="<?php echo $safe; ?>" class="w-full h-full object-cover" alt="Complaint photo">
                  </button>
                <?php endforeach; ?>
              </div>
              <p class="text-xs text-slate-500 mt-3">Tip: click any image to preview.</p>
            <?php endif; ?>
          </div>

          <!-- Admin Note -->
          <?php if (!empty($complaint["admin_note"])): ?>
            <div class="mt-6 p-5 bg-blue-50 rounded-xl border-2 border-blue-200">
              <p class="text-sm font-semibold text-blue-900 mb-2">
                <i class="fas fa-sticky-note text-blue-600 mr-2"></i>Admin Note
              </p>
              <p class="text-slate-800 leading-relaxed"><?php echo nl2br(htmlspecialchars($complaint["admin_note"])); ?></p>
            </div>
          <?php endif; ?>

          <!-- Resolution Message -->
          <?php if (!empty($complaint["resolution_message"])): ?>
            <div class="mt-6 p-5 bg-green-50 rounded-xl border-2 border-green-200">
              <p class="text-sm font-semibold text-green-900 mb-2">
                <i class="fas fa-check-circle text-green-600 mr-2"></i>Resolution Message
              </p>
              <p class="text-slate-800 leading-relaxed"><?php echo nl2br(htmlspecialchars($complaint["resolution_message"])); ?></p>
            </div>
          <?php endif; ?>

          <!-- Status History -->
          <div class="mt-8">
            <h3 class="text-lg font-semibold text-slate-800 mb-4 flex items-center gap-2">
              <i class="fas fa-clock-rotate-left text-blue-600"></i>
              Status History
            </h3>

            <?php if (empty($timeline)): ?>
              <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 text-slate-600">
                No status updates yet.
              </div>
            <?php else: ?>
              <div class="relative pl-6 space-y-5">
                <div class="absolute left-2 top-1 bottom-1 w-0.5 bg-slate-200"></div>

                <?php foreach ($timeline as $item):
                  $meta = statusMeta($item["status"], $statusConfig);
                  $dateLabel = date("M d, Y H:i", strtotime($item["created_at"]));
                  $note = trim($item["note"] ?? "");
                ?>
                  <div class="relative">
                    <div class="absolute -left-[2px] top-1.5 w-4 h-4 rounded-full bg-<?php echo $meta["color"]; ?>-600 border-4 border-white shadow"></div>

                    <div class="bg-slate-50 border border-slate-200 rounded-xl p-4">
                      <div class="flex items-start justify-between gap-3 flex-wrap">
                        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-<?php echo $meta["color"]; ?>-100 text-<?php echo $meta["color"]; ?>-800 font-bold text-sm">
                          <i class="fas <?php echo $meta["icon"]; ?>"></i>
                          <?php echo htmlspecialchars($meta["label"]); ?>
                        </span>

                        <span class="text-sm text-slate-500 font-medium">
                          <?php echo htmlspecialchars($dateLabel); ?>
                        </span>
                      </div>

                      <?php if ($note !== ""): ?>
                        <p class="mt-3 text-slate-700 leading-relaxed">
                          <?php echo nl2br(htmlspecialchars($note)); ?>
                        </p>
                      <?php endif; ?>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>
          </div>

        </div>
      </div>
    <?php endif; ?>

  </div>
</section>

<!-- ✅ Image Preview Modal -->
<div id="imgModal" class="fixed inset-0 z-[9999] hidden">
  <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeImageModal()"></div>

  <div class="relative min-h-[100svh] flex items-center justify-center p-4">
    <div class="w-full max-w-4xl bg-white rounded-2xl overflow-hidden shadow-2xl border border-slate-200">
      <div class="flex items-center justify-between px-4 py-3 bg-slate-50 border-b border-slate-200">
        <p class="font-extrabold text-slate-900">Photo Preview</p>
        <button class="w-10 h-10 rounded-xl bg-white border border-slate-200 hover:bg-slate-100"
                onclick="closeImageModal()"
                aria-label="Close">✕</button>
      </div>
      <div class="p-3 bg-black">
        <img id="imgModalTarget" src="" alt="Preview" class="w-full max-h-[75vh] object-contain rounded-xl bg-black" />
      </div>
    </div>
  </div>
</div>

<script>
  const imgModal = document.getElementById("imgModal");
  const imgTarget = document.getElementById("imgModalTarget");

  function openImageModal(src) {
    if (!imgModal || !imgTarget) return;
    imgTarget.src = src;
    imgModal.classList.remove("hidden");
    document.body.style.overflow = "hidden";
  }

  function closeImageModal() {
    if (!imgModal || !imgTarget) return;
    imgTarget.src = "";
    imgModal.classList.add("hidden");
    document.body.style.overflow = "";
  }

  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape" && imgModal && !imgModal.classList.contains("hidden")) {
      closeImageModal();
    }
  });
</script>

<?php include "footer.php"; ?>
