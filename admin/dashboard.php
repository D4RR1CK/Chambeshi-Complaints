<?php
require_once "admin_auth.php";
requireAdmin();

require_once "../db.php";

$statusFilter = trim($_GET["status"] ?? "");
$search = trim($_GET["q"] ?? "");

/* ===================== FIX: PHOTO URL HELPER ===================== */
function photoUrl(string $path): string {
    $path = trim($path);
    if ($path === "") return "";

    // If already absolute http(s), keep it
    if (preg_match('#^https?://#i', $path)) return $path;

    // Remove leading slashes so "../" works correctly
    $path = ltrim($path, "/");

    // dashboard.php is in /admin, so go up one level
    return "../" . $path;
}

/* ===================== QUERY COMPLAINTS ===================== */
$sql = "SELECT c.id, c.tracking_id, c.room_number, c.issue, c.previous_issue, c.status, c.created_at,
               c.admin_note, c.resolution_message,
               u.fullname, u.email
        FROM complaints c
        JOIN users u ON u.id = c.user_id
        WHERE 1=1";

$params = [];
$types = "";

if ($statusFilter !== "") {
    $sql .= " AND c.status = ?";
    $params[] = $statusFilter;
    $types .= "s";
}

if ($search !== "") {
    $sql .= " AND (c.tracking_id LIKE ? OR c.issue LIKE ? OR u.fullname LIKE ? OR u.email LIKE ?)";
    $like = "%" . $search . "%";
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
    $types .= "ssss";
}

$sql .= " ORDER BY c.created_at DESC";

$stmt = $conn->prepare($sql);
if ($types !== "") {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$res = $stmt->get_result();
$rows = $res->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$adminName = $_SESSION["admin_name"] ?? "Admin";

/* ===================== STATUS COUNTS ===================== */
$statusCounts = ["submitted" => 0, "in review" => 0, "in progress" => 0, "resolved" => 0];
foreach ($rows as $r) {
    if (isset($statusCounts[$r["status"]])) {
        $statusCounts[$r["status"]]++;
    }
}

/* ===================== LOAD PHOTOS (MULTI) ===================== */
$photosByComplaint = []; // [complaint_id => [file_path, file_path...]]
if (!empty($rows)) {
    $ids = array_map(fn($r) => (int)$r["id"], $rows);
    $ids = array_values(array_unique($ids));

    $placeholders = implode(",", array_fill(0, count($ids), "?"));
    $photoSql = "SELECT complaint_id, file_path
                 FROM complaint_photos
                 WHERE complaint_id IN ($placeholders)
                 ORDER BY id ASC";

    $pstmt = $conn->prepare($photoSql);
    if ($pstmt) {
        $ptypes = str_repeat("i", count($ids));
        $pstmt->bind_param($ptypes, ...$ids);
        $pstmt->execute();
        $pres = $pstmt->get_result();
        while ($p = $pres->fetch_assoc()) {
            $cid = (int)$p["complaint_id"];
            if (!isset($photosByComplaint[$cid])) {
                $photosByComplaint[$cid] = [];
            }
            $photosByComplaint[$cid][] = $p["file_path"];
        }
        $pstmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Chambeshi Complaints</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
      @keyframes slideDown {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
      }
      @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
      }
      @keyframes scaleIn {
        from { opacity: 0; transform: scale(0.9); }
        to { opacity: 1; transform: scale(1); }
      }
      @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.8; }
      }

      .animate-slide-down { animation: slideDown 0.5s ease-out; }
      .animate-fade-in { animation: fadeIn 0.4s ease-out; }
      .animate-scale-in { animation: scaleIn 0.3s ease-out; }
      
      .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
      }
      
      .history-panel {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.4s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.3s ease;
        opacity: 0;
      }
      .history-panel.open { 
        max-height: 600px; 
        opacity: 1; 
      }
      
      .glass-effect { 
        backdrop-filter: blur(16px) saturate(180%);
        background: rgba(255,255,255,0.92);
        border: 1px solid rgba(255,255,255,0.3);
      }
      
      .stat-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      }
      .stat-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
      }
      
      .table-row {
        transition: all 0.2s ease;
      }
      .table-row:hover {
        background: linear-gradient(to right, rgba(239, 246, 255, 0.5), rgba(219, 234, 254, 0.3));
        transform: translateX(4px);
      }
      
      .btn-action {
        position: relative;
        overflow: hidden;
        transition: all 0.2s ease;
      }
      .btn-action::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.3);
        transform: translate(-50%, -50%);
        transition: width 0.5s, height 0.5s;
      }
      .btn-action:hover::before {
        width: 300px;
        height: 300px;
      }
      
      .status-badge {
        transition: all 0.2s ease;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      }
      .status-badge:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
      }
      
      .photo-thumbnail {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      }
      .photo-thumbnail:hover {
        transform: scale(1.1) rotate(2deg);
        z-index: 10;
      }
      
      input:focus, select:focus, textarea:focus {
        transform: scale(1.01);
        transition: transform 0.2s ease;
      }
      
      /* Custom scrollbar */
      ::-webkit-scrollbar {
        width: 10px;
        height: 10px;
      }
      ::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 10px;
      }
      ::-webkit-scrollbar-thumb {
        background: linear-gradient(to bottom, #3b82f6, #2563eb);
        border-radius: 10px;
      }
      ::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(to bottom, #2563eb, #1d4ed8);
      }

      /* Responsive table scroll indicator */
      .table-container {
        position: relative;
      }
      .table-container::after {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        width: 30px;
        background: linear-gradient(to left, rgba(255,255,255,0.9), transparent);
        pointer-events: none;
        opacity: 0;
        transition: opacity 0.3s;
      }
      .table-container.scrollable::after {
        opacity: 1;
      }

      /* Mobile menu animation */
      @media (max-width: 768px) {
        .mobile-menu {
          max-height: 0;
          overflow: hidden;
          transition: max-height 0.3s ease-out;
        }
        .mobile-menu.open {
          max-height: 500px;
        }
      }
    </style>
</head>

<body class="bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 min-h-screen">
<div class="max-w-[1600px] mx-auto p-3 sm:p-4 md:p-6 lg:p-8">

    <!-- Header -->
    <div class="glass-effect rounded-3xl shadow-2xl p-4 sm:p-6 lg:p-8 mb-6 lg:mb-8 animate-slide-down">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex-1">
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-black bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 bg-clip-text text-transparent flex items-center gap-2 sm:gap-3">
                    <i class="fas fa-tachometer-alt text-blue-600"></i>
                    <span>Admin Dashboard</span>
                </h1>
                <p class="text-slate-600 mt-2 sm:mt-3 flex items-center gap-2 text-sm sm:text-base">
                    <i class="fas fa-user-shield text-blue-600"></i>
                    Welcome back, <span class="font-bold text-blue-700"><?php echo htmlspecialchars($adminName); ?></span>
                </p>
            </div>
            <a href="logout.php"
               class="btn-action w-full sm:w-auto px-6 py-3 bg-gradient-to-r from-red-500 to-red-600 text-white font-bold rounded-xl hover:from-red-600 hover:to-red-700 transition-all shadow-lg hover:shadow-xl flex items-center justify-center gap-2 relative overflow-hidden group">
                <i class="fas fa-sign-out-alt group-hover:rotate-12 transition-transform"></i>
                <span>Logout</span>
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-6 mb-6 lg:mb-8">
        <?php
        $stats = [
            ["label" => "Submitted", "count" => $statusCounts["submitted"], "icon" => "fa-inbox", "gradient" => "from-blue-500 to-blue-600", "bg" => "bg-blue-50"],
            ["label" => "In Review", "count" => $statusCounts["in review"], "icon" => "fa-search", "gradient" => "from-yellow-500 to-yellow-600", "bg" => "bg-yellow-50"],
            ["label" => "In Progress", "count" => $statusCounts["in progress"], "icon" => "fa-spinner", "gradient" => "from-orange-500 to-orange-600", "bg" => "bg-orange-50"],
            ["label" => "Resolved", "count" => $statusCounts["resolved"], "icon" => "fa-check-circle", "gradient" => "from-green-500 to-green-600", "bg" => "bg-green-50"]
        ];
        $delay = 0;
        foreach ($stats as $stat):
            $delay += 0.1;
        ?>
        <div class="stat-card glass-effect rounded-2xl shadow-xl p-4 sm:p-6 hover:shadow-2xl animate-fade-in <?php echo $stat["bg"]; ?>" style="animation-delay: <?php echo $delay; ?>s">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
                <div class="text-center sm:text-left w-full">
                    <p class="text-slate-600 text-xs sm:text-sm font-bold uppercase tracking-wider"><?php echo htmlspecialchars($stat["label"]); ?></p>
                    <p class="text-3xl sm:text-4xl lg:text-5xl font-black bg-gradient-to-r <?php echo htmlspecialchars($stat["gradient"]); ?> bg-clip-text text-transparent mt-1 sm:mt-2">
                        <?php echo (int)$stat["count"]; ?>
                    </p>
                </div>
                <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-2xl bg-gradient-to-br <?php echo htmlspecialchars($stat["gradient"]); ?> flex items-center justify-center shadow-lg flex-shrink-0">
                    <i class="fas <?php echo htmlspecialchars($stat["icon"]); ?> text-white text-xl sm:text-2xl"></i>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Filters -->
    <div class="glass-effect rounded-2xl shadow-xl p-4 sm:p-6 mb-6 lg:mb-8 animate-slide-down">
        <div class="flex items-center gap-3 mb-4 sm:mb-5">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-lg">
                <i class="fas fa-filter text-white"></i>
            </div>
            <h2 class="text-lg sm:text-xl font-bold text-slate-800">Search & Filter</h2>
        </div>
        <form method="GET" class="flex flex-col gap-3 sm:gap-4">
            <div class="flex-1 relative group">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-blue-600 transition-colors z-10"></i>
                <input type="text" name="q" value="<?php echo htmlspecialchars($search); ?>"
                       placeholder="Search by tracking ID, issue, name, or email..."
                       class="w-full pl-12 pr-4 py-3 sm:py-3.5 border-2 border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none bg-white hover:border-blue-300 transition-all text-sm sm:text-base">
            </div>

            <div class="flex flex-col sm:flex-row gap-3">
                <select name="status"
                        class="flex-1 px-4 py-3 sm:py-3.5 border-2 border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none bg-white hover:border-blue-300 font-medium transition-all text-sm sm:text-base">
                    <option value="">All Statuses</option>
                    <?php foreach (["submitted","in review","in progress","resolved"] as $s): ?>
                        <option value="<?php echo htmlspecialchars($s); ?>" <?php echo ($statusFilter === $s) ? "selected" : ""; ?>>
                            <?php echo htmlspecialchars(ucwords($s)); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <button type="submit"
                        class="btn-action px-6 sm:px-8 py-3 sm:py-3.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all shadow-lg hover:shadow-xl flex items-center justify-center gap-2 relative overflow-hidden">
                    <i class="fas fa-search"></i>
                    <span>Apply Filters</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="glass-effect rounded-2xl shadow-xl border border-white/50 overflow-hidden animate-scale-in">
        <div class="p-4 sm:p-6 border-b border-slate-200 bg-gradient-to-r from-slate-50 to-blue-50">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-lg">
                        <i class="fas fa-list text-white"></i>
                    </div>
                    <h2 class="text-lg sm:text-xl font-bold text-slate-800">Complaints Management</h2>
                </div>
                <div class="flex items-center gap-2 bg-white px-4 py-2 rounded-xl shadow-sm border border-slate-200">
                    <i class="fas fa-database text-blue-600"></i>
                    <span class="text-sm font-bold text-slate-600"><?php echo count($rows); ?> Total</span>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto table-container">
            <table class="w-full min-w-[1000px]">
                <thead class="bg-gradient-to-r from-slate-100 to-blue-100 border-b-2 border-slate-300 sticky top-0 z-10">
                    <tr class="text-left">
                        <th class="p-3 sm:p-4 text-xs sm:text-sm font-bold text-slate-700 uppercase tracking-wider whitespace-nowrap">Tracking ID</th>
                        <th class="p-3 sm:p-4 text-xs sm:text-sm font-bold text-slate-700 uppercase tracking-wider whitespace-nowrap">User</th>
                        <th class="p-3 sm:p-4 text-xs sm:text-sm font-bold text-slate-700 uppercase tracking-wider whitespace-nowrap">Room</th>
                        <th class="p-3 sm:p-4 text-xs sm:text-sm font-bold text-slate-700 uppercase tracking-wider whitespace-nowrap">Issue & Photos</th>
                        <th class="p-3 sm:p-4 text-xs sm:text-sm font-bold text-slate-700 uppercase tracking-wider whitespace-nowrap">Status</th>
                        <th class="p-3 sm:p-4 text-xs sm:text-sm font-bold text-slate-700 uppercase tracking-wider whitespace-nowrap min-w-[350px]">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100 bg-white/80">
                <?php if (empty($rows)): ?>
                    <tr>
                        <td class="p-8 sm:p-12 text-center text-slate-500" colspan="6">
                            <div class="flex flex-col items-center gap-4">
                                <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-slate-100 flex items-center justify-center">
                                    <i class="fas fa-inbox text-4xl sm:text-5xl text-slate-300"></i>
                                </div>
                                <div>
                                    <p class="text-base sm:text-lg font-bold">No complaints found</p>
                                    <p class="text-sm text-slate-400 mt-1">Try adjusting your filters</p>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php
                    $statusColors = [
                        "submitted"   => "bg-gradient-to-r from-blue-500 to-blue-600 text-white",
                        "in review"   => "bg-gradient-to-r from-yellow-500 to-yellow-600 text-white",
                        "in progress" => "bg-gradient-to-r from-orange-500 to-orange-600 text-white",
                        "resolved"    => "bg-gradient-to-r from-green-500 to-green-600 text-white"
                    ];
                    ?>

                    <?php foreach ($rows as $r):
                        $cid = (int)$r["id"];
                        $photos = $photosByComplaint[$cid] ?? [];
                        $statusColor = $statusColors[$r["status"]] ?? "bg-gray-200 text-gray-800";
                    ?>
                        <tr class="table-row align-top border-l-4 border-transparent hover:border-blue-500">
                            <td class="p-3 sm:p-4">
                                <span class="font-mono text-xs sm:text-sm font-bold text-blue-700 bg-blue-100 px-3 py-2 rounded-xl inline-block shadow-sm border border-blue-200 whitespace-nowrap">
                                    <?php echo htmlspecialchars($r["tracking_id"]); ?>
                                </span>
                            </td>

                            <td class="p-3 sm:p-4">
                                <div class="font-bold text-slate-800 text-sm sm:text-base"><?php echo htmlspecialchars($r["fullname"]); ?></div>
                                <div class="text-xs sm:text-sm text-slate-500 flex items-center gap-1 mt-1">
                                    <i class="fas fa-envelope text-blue-500 text-xs"></i>
                                    <span class="truncate max-w-[200px]"><?php echo htmlspecialchars($r["email"]); ?></span>
                                </div>
                            </td>

                            <td class="p-3 sm:p-4">
                                <span class="inline-flex items-center gap-2 text-slate-700 bg-slate-100 px-3 py-2 rounded-lg font-bold text-sm">
                                    <i class="fas fa-door-open text-slate-500"></i>
                                    <?php echo htmlspecialchars($r["room_number"] ?: "N/A"); ?>
                                </span>
                            </td>

                            <td class="p-3 sm:p-4 max-w-xs">
                                <div class="text-slate-800 line-clamp-2 font-medium text-sm sm:text-base"><?php echo htmlspecialchars($r["issue"]); ?></div>
                                <div class="text-xs text-slate-500 mt-2 flex items-center gap-2 bg-slate-50 px-2 py-1 rounded-lg inline-flex">
                                    <i class="fas fa-clock text-slate-400"></i>
                                    <?php echo htmlspecialchars($r["created_at"]); ?>
                                </div>

                                <!-- Photos -->
                                <div class="mt-3">
                                    <p class="text-xs font-bold text-slate-700 flex items-center gap-2">
                                        <i class="fas fa-image text-indigo-600"></i>
                                        Photos
                                        <span class="bg-slate-200 text-slate-700 px-2 py-0.5 rounded-full font-mono text-[10px]">
                                            <?php echo count($photos); ?>
                                        </span>
                                    </p>

                                    <?php if (empty($photos)): ?>
                                        <div class="mt-2 text-xs text-slate-500 bg-slate-50 border border-slate-200 rounded-lg p-2">
                                            No photos uploaded.
                                        </div>
                                    <?php else: ?>
                                        <div class="mt-2 flex flex-wrap gap-2">
                                            <?php foreach ($photos as $p):
                                                $url = photoUrl($p);
                                                $safeUrl = htmlspecialchars($url);
                                            ?>
                                                <button type="button"
                                                    class="photo-thumbnail group w-14 h-14 sm:w-16 sm:h-16 rounded-lg overflow-hidden border-2 border-slate-200 bg-white shadow-sm hover:shadow-lg transition-all relative"
                                                    onclick="openImageModal('<?php echo $safeUrl; ?>')"
                                                    title="Click to preview">
                                                    <img src="<?php echo $safeUrl; ?>"
                                                         class="w-full h-full object-cover"
                                                         alt="Complaint photo"
                                                         loading="lazy">
                                                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-all flex items-center justify-center">
                                                        <i class="fas fa-search-plus text-white opacity-0 group-hover:opacity-100 transition-opacity"></i>
                                                    </div>
                                                </button>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </td>

                            <td class="p-3 sm:p-4">
                                <span class="status-badge inline-block px-3 sm:px-4 py-2 text-xs font-bold rounded-xl <?php echo $statusColor; ?> whitespace-nowrap">
                                    <?php echo htmlspecialchars(ucwords($r["status"])); ?>
                                </span>
                            </td>

                            <td class="p-3 sm:p-4">
                                <form method="POST" action="update_status.php" class="space-y-3">
                                    <input type="hidden" name="id" value="<?php echo $cid; ?>">

                                    <div class="flex flex-col sm:flex-row gap-2 items-stretch sm:items-center">
                                        <select name="status" class="flex-1 px-3 py-2.5 border-2 border-slate-300 rounded-lg text-sm font-medium focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none hover:border-slate-400 transition-all">
                                            <?php foreach (["submitted","in review","in progress","resolved"] as $s): ?>
                                                <option value="<?php echo htmlspecialchars($s); ?>" <?php echo ($r["status"] === $s) ? "selected" : ""; ?>>
                                                    <?php echo htmlspecialchars(ucwords($s)); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>

                                        <button type="submit" class="btn-action px-5 py-2.5 bg-gradient-to-r from-green-600 to-green-700 text-white font-bold rounded-lg hover:from-green-700 hover:to-green-800 transition-all shadow-md hover:shadow-lg flex items-center justify-center gap-2 whitespace-nowrap relative overflow-hidden">
                                            <i class="fas fa-save"></i>
                                            <span>Save</span>
                                        </button>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-2 flex items-center gap-2">
                                            <i class="fas fa-sticky-note text-yellow-600"></i> Admin Note
                                        </label>
                                        <textarea name="admin_note" rows="2"
                                            class="w-full p-3 text-sm border-2 border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none resize-none hover:border-slate-400 transition-all"
                                            placeholder="Internal notes..."><?php echo htmlspecialchars($r["admin_note"] ?? ""); ?></textarea>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-2 flex items-center gap-2">
                                            <i class="fas fa-comment-dots text-blue-600"></i> Resolution Message
                                        </label>
                                        <textarea name="resolution_message" rows="2"
                                            class="w-full p-3 text-sm border-2 border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none resize-none hover:border-slate-400 transition-all"
                                            placeholder="Student-facing message..."><?php echo htmlspecialchars($r["resolution_message"] ?? ""); ?></textarea>
                                    </div>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Image Preview Modal -->
<div id="imgModal" class="fixed inset-0 z-[9999] hidden">
  <div class="absolute inset-0 bg-black/70 backdrop-blur-md transition-all" onclick="closeImageModal()"></div>

  <div class="relative min-h-screen flex items-center justify-center p-3 sm:p-4">
    <div class="w-full max-w-5xl bg-white rounded-3xl overflow-hidden shadow-2xl border-2 border-white/50 animate-scale-in">
      <div class="flex items-center justify-between px-4 sm:px-6 py-3 sm:py-4 bg-gradient-to-r from-slate-50 to-blue-50 border-b-2 border-slate-200">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-lg">
                <i class="fas fa-image text-white"></i>
            </div>
            <p class="font-black text-slate-900 text-base sm:text-lg">Photo Preview</p>
        </div>
        <button class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-white border-2 border-slate-200 hover:bg-red-50 hover:border-red-300 transition-all shadow-lg hover:shadow-xl group"
                onclick="closeImageModal()"
                aria-label="Close">
            <i class="fas fa-times text-slate-600 group-hover:text-red-600 transition-colors"></i>
        </button>
      </div>
      <div class="p-3 sm:p-4 bg-black">
        <img id="imgModalTarget" src="" alt="Preview" class="w-full max-h-[70vh] sm:max-h-[75vh] object-contain rounded-xl" />
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

  // Check if table is scrollable and add indicator
  function checkTableScroll() {
    const container = document.querySelector('.table-container');
    const table = container?.querySelector('table');
    if (container && table) {
      if (table.scrollWidth > container.clientWidth) {
        container.classList.add('scrollable');
      } else {
        container.classList.remove('scrollable');
      }
    }
  }

  window.addEventListener('load', checkTableScroll);
  window.addEventListener('resize', checkTableScroll);
</script>
</body>
</html>