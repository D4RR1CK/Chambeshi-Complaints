<?php
$pageTitle = "My Issues";
include "header.php";
require_once "db.php";

if (!isset($_SESSION["user_id"])) {
  header("Location: login.php");
  exit;
}

$userId = (int)$_SESSION["user_id"];

$sql = "SELECT tracking_id, room_number, issue, status, created_at
        FROM complaints
        WHERE user_id = ?
        ORDER BY created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$res = $stmt->get_result();
$issues = $res->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Status configuration
$statusConfig = [
    "submitted" => ["icon" => "fa-inbox", "color" => "blue"],
    "in review" => ["icon" => "fa-search", "color" => "yellow"],
    "in progress" => ["icon" => "fa-spinner", "color" => "orange"],
    "resolved" => ["icon" => "fa-check-circle", "color" => "green"]
];

// Count by status
$statusCounts = ["submitted" => 0, "in review" => 0, "in progress" => 0, "resolved" => 0];
foreach ($issues as $i) {
    if (isset($statusCounts[$i["status"]])) {
        $statusCounts[$i["status"]]++;
    }
}
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<section class="bg-gradient-to-br from-slate-50 to-blue-50 py-12 min-h-screen">
  <div class="max-w-6xl mx-auto px-4">
    <!-- Header -->
    <div class="text-center mb-10">
      <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gradient-to-br from-blue-600 to-indigo-600 shadow-lg mb-4">
        <i class="fas fa-clipboard-list text-white text-2xl"></i>
      </div>
      <h1 class="text-4xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
        My Issues
      </h1>
      <p class="text-slate-600 mt-3 text-lg">
        Track and manage all your submitted complaints
      </p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
      <?php
      $stats = [
        ["label" => "Submitted", "count" => $statusCounts["submitted"], "icon" => "fa-inbox", "color" => "blue"],
        ["label" => "In Review", "count" => $statusCounts["in review"], "icon" => "fa-search", "color" => "yellow"],
        ["label" => "In Progress", "count" => $statusCounts["in progress"], "icon" => "fa-spinner", "color" => "orange"],
        ["label" => "Resolved", "count" => $statusCounts["resolved"], "icon" => "fa-check-circle", "color" => "green"]
      ];
      foreach ($stats as $stat):
      ?>
      <div class="bg-white rounded-xl shadow-md p-5 border border-slate-200 hover:shadow-lg transition-shadow">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-slate-600 text-sm font-medium"><?php echo $stat["label"]; ?></p>
            <p class="text-3xl font-bold text-slate-800 mt-1"><?php echo $stat["count"]; ?></p>
          </div>
          <div class="w-12 h-12 rounded-full bg-<?php echo $stat["color"]; ?>-100 flex items-center justify-center">
            <i class="fas <?php echo $stat["icon"]; ?> text-<?php echo $stat["color"]; ?>-600 text-lg"></i>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- Issues List -->
    <div class="bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden">
      <div class="p-6 border-b border-slate-200 bg-gradient-to-r from-slate-50 to-blue-50">
        <div class="flex items-center justify-between flex-wrap gap-3">
          <div class="flex items-center gap-3">
            <i class="fas fa-list text-blue-600 text-xl"></i>
            <h2 class="text-xl font-semibold text-slate-800">All Complaints</h2>
          </div>
          <div class="flex items-center gap-2 text-sm">
            <span class="px-3 py-1.5 bg-blue-100 text-blue-700 font-semibold rounded-full">
              <?php echo count($issues); ?> Total
            </span>
          </div>
        </div>
      </div>

      <?php if (empty($issues)): ?>
        <div class="p-12 text-center">
          <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-yellow-100 mb-4">
            <i class="fas fa-inbox text-yellow-600 text-3xl"></i>
          </div>
          <h3 class="text-xl font-semibold text-slate-800 mb-2">No Issues Yet</h3>
          <p class="text-slate-600 mb-6">You haven't submitted any complaints yet.</p>

          <!-- ✅ FIXED LINK HERE -->
          <a href="reporting.php" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all shadow-lg hover:shadow-xl">
            <i class="fas fa-plus"></i>
            Submit New Issue
          </a>
        </div>
      <?php else: ?>
        <!-- Desktop Table View -->
        <div class="hidden md:block overflow-x-auto">
          <table class="w-full">
            <thead class="bg-slate-50 border-b border-slate-200">
              <tr class="text-left">
                <th class="p-4 text-sm font-semibold text-slate-700">Tracking ID</th>
                <th class="p-4 text-sm font-semibold text-slate-700">Room</th>
                <th class="p-4 text-sm font-semibold text-slate-700">Issue</th>
                <th class="p-4 text-sm font-semibold text-slate-700">Status</th>
                <th class="p-4 text-sm font-semibold text-slate-700">Submitted</th>
                <th class="p-4 text-sm font-semibold text-slate-700">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
              <?php foreach ($issues as $i):
                $status = $i["status"];
                $statusInfo = $statusConfig[$status] ?? ["icon" => "fa-question", "color" => "gray"];
              ?>
                <tr class="hover:bg-slate-50 transition-colors">
                  <td class="p-4">
                    <div class="flex items-center gap-2">
                      <i class="fas fa-barcode text-slate-400"></i>
                      <span class="font-mono font-bold text-blue-600">
                        <?php echo htmlspecialchars($i["tracking_id"]); ?>
                      </span>
                    </div>
                  </td>
                  <td class="p-4">
                    <div class="flex items-center gap-2 text-slate-700">
                      <i class="fas fa-door-open text-slate-400"></i>
                      <?php echo htmlspecialchars($i["room_number"] ?: "N/A"); ?>
                    </div>
                  </td>
                  <td class="p-4 max-w-xs">
                    <p class="text-slate-800 line-clamp-2">
                      <?php echo htmlspecialchars($i["issue"]); ?>
                    </p>
                  </td>
                  <td class="p-4">
                    <span class="inline-flex items-center gap-2 px-3 py-1.5 text-xs font-semibold rounded-full bg-<?php echo $statusInfo['color']; ?>-100 text-<?php echo $statusInfo['color']; ?>-800">
                      <i class="fas <?php echo $statusInfo['icon']; ?>"></i>
                      <?php echo htmlspecialchars(ucwords($status)); ?>
                    </span>
                  </td>
                  <td class="p-4">
                    <div class="flex items-center gap-2 text-sm text-slate-600">
                      <i class="fas fa-calendar text-slate-400"></i>
                      <?php echo htmlspecialchars(date('M d, Y', strtotime($i["created_at"]))); ?>
                    </div>
                  </td>
                  <td class="p-4">
                    <form action="tracking.php" method="POST">
                      <input type="hidden" name="tracking_id" value="<?php echo htmlspecialchars($i["tracking_id"]); ?>">
                      <button class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all shadow-sm hover:shadow-md">
                        <i class="fas fa-eye"></i>
                        View
                      </button>
                    </form>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

        <!-- Mobile Card View -->
        <div class="md:hidden divide-y divide-slate-100">
          <?php foreach ($issues as $i):
            $status = $i["status"];
            $statusInfo = $statusConfig[$status] ?? ["icon" => "fa-question", "color" => "gray"];
          ?>
            <div class="p-5 hover:bg-slate-50 transition-colors">
              <div class="flex items-start justify-between mb-3">
                <div class="flex-1">
                  <div class="flex items-center gap-2 mb-2">
                    <i class="fas fa-barcode text-slate-400"></i>
                    <span class="font-mono font-bold text-blue-600 text-sm">
                      <?php echo htmlspecialchars($i["tracking_id"]); ?>
                    </span>
                  </div>
                  <span class="inline-flex items-center gap-2 px-3 py-1 text-xs font-semibold rounded-full bg-<?php echo $statusInfo['color']; ?>-100 text-<?php echo $statusInfo['color']; ?>-800">
                    <i class="fas <?php echo $statusInfo['icon']; ?>"></i>
                    <?php echo htmlspecialchars(ucwords($status)); ?>
                  </span>
                </div>
              </div>

              <div class="space-y-2 mb-3">
                <div class="flex items-center gap-2 text-sm text-slate-600">
                  <i class="fas fa-door-open text-slate-400 w-4"></i>
                  <span>Room: <strong><?php echo htmlspecialchars($i["room_number"] ?: "N/A"); ?></strong></span>
                </div>
                <div class="flex items-center gap-2 text-sm text-slate-600">
                  <i class="fas fa-calendar text-slate-400 w-4"></i>
                  <span><?php echo htmlspecialchars(date('M d, Y', strtotime($i["created_at"]))); ?></span>
                </div>
              </div>

              <p class="text-slate-800 text-sm mb-4 line-clamp-2">
                <?php echo htmlspecialchars($i["issue"]); ?>
              </p>

              <form action="tracking.php" method="POST">
                <input type="hidden" name="tracking_id" value="<?php echo htmlspecialchars($i["tracking_id"]); ?>">
                <button class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all shadow-sm hover:shadow-md">
                  <i class="fas fa-eye"></i>
                  View Details
                </button>
              </form>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>

    <!-- Help Section -->
    <?php if (!empty($issues)): ?>
    <div class="mt-8 p-5 bg-blue-50 rounded-xl border border-blue-200">
      <div class="flex items-start gap-3">
        <i class="fas fa-info-circle text-blue-600 text-xl mt-1"></i>
        <div>
          <p class="font-semibold text-blue-900 mb-1">Quick Tip</p>
          <p class="text-blue-800 text-sm">
            Click "View" on any issue to see detailed tracking information and status updates. Keep your tracking IDs safe for future reference.
          </p>
        </div>
      </div>
    </div>
    <?php endif; ?>
  </div>
</section>

<?php include "footer.php"; ?>
