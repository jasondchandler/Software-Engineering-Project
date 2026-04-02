<?php 
session_start(); 
require_once("connect.php");
?>
<head>
    <title>Charles Casale - Cases</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <script src="site.js" defer></script>
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</head>

<body>
<?php include "nav.php"; ?>

<div class="main">
<?php
if (empty($_SESSION["user_id"])) {
    $_SESSION["login_error"] = "Please log in.";
    header("Location: login.php");
    exit;
}

function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

$pendingCount = 0;
$ongoingCount = 0;
$closedCount = 0;

$r1 = $conn->query("SELECT COUNT(*) AS c FROM cases WHERE status='pending'");
$pendingCount = $r1 ? (int)$r1->fetch_assoc()["c"] : 0;

$r2 = $conn->query("SELECT COUNT(*) AS c FROM cases WHERE status='ongoing'");
$ongoingCount = $r2 ? (int)$r2->fetch_assoc()["c"] : 0;

$r3 = $conn->query("SELECT COUNT(*) AS c FROM cases WHERE status='closed'");
$closedCount = $r3 ? (int)$r3->fetch_assoc()["c"] : 0;
?>

<div class="container">
    <div class="nav">
        <div>
            <h1>Case Dashboard</h1>
            <div class="small">Welcome back, <?php echo h($_SESSION["name"]); ?></div>
        </div>
    </div>

    <div class="stats d-flex justify-content-between mb-3">
        <div class="stat text-center p-2 bg-light rounded">
            <div class="small">Pending Cases</div>
            <div class="num fs-4"><?php echo $pendingCount; ?></div>
            <div class="small">Awaiting review</div>
        </div>
        <div class="stat text-center p-2 bg-info text-white rounded">
            <div class="small">Ongoing Cases</div>
            <div class="num fs-4"><?php echo $ongoingCount; ?></div>
            <div class="small">In progress</div>
        </div>
        <div class="stat text-center p-2 bg-success text-white rounded">
            <div class="small">Closed Cases</div>
            <div class="num fs-4"><?php echo $closedCount; ?></div>
            <div class="small">Completed</div>
        </div>
    </div>

    <button type="button" class="btn btn-dark w-100 mb-3" data-bs-toggle="modal" data-bs-target="#createCaseForm"> 
      Create Case
    </button>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Court</th>
                <th>Type</th>
                <th>Filing Date</th>
                <th>Status</th>
                <th>Assigned To</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT c.*, u.firstname, u.lastname 
                    FROM cases c 
                    LEFT JOIN users u ON c.assigned_to = u.user_id 
                    ORDER BY c.filing_date DESC";
            $result = $conn->query($sql);

            if($result && $result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>".h($row['case_id'])."</td>";
                    echo "<td>".h($row['title'])."</td>";
                    echo "<td>".h($row['court'])."</td>";
                    echo "<td>".h($row['type'])."</td>";
                    echo "<td>".h($row['filing_date'])."</td>";
                    echo "<td>".h($row['status'])."</td>";
                    echo "<td>".($row['firstname'] ? h($row['firstname']." ".$row['lastname']) : "Unassigned")."</td>";
                    echo "<td>
                            <a href='edit_case.php?case_id=".$row['case_id']."' class='btn btn-sm btn-primary'>Edit</a>
                            <a href='delete_case.php?case_id=".$row['case_id']."' class='btn btn-sm btn-danger'>Delete</a>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='8'>No cases found</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <div class="modal fade" id="createCaseForm" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Create Case</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="create_case_action.php" method="POST">
              <label>Case Title:</label><br>
              <input class="form-control" type="text" name="title" maxlength="50" required><br><br>

              <label>Court:</label><br>
              <input class="form-control" type="text" name="court" maxlength="50" required><br><br>

              <label>Case Type:</label><br>
              <input class="form-control" type="text" name="type" maxlength="20" required><br><br>

              <label>Filing Date:</label><br>
              <input class="form-control" type="date" name="filing_date" required><br><br>

              <label>Status:</label><br>
              <select class="form-control mb-3" name="status" required>
                  <option value="">--Select Status--</option>
                  <option value="Open">Open</option>
                  <option value="Closed">Closed</option>
                  <option value="Pending">Pending</option>
                  <option value="Appeal">Appeal</option>
              </select><br><br>

              <button type="submit" class="btn btn-primary form-control">Create Case</button>
            </form>
          </div>
        </div>
      </div>
    </div>

</div>
</div>
</body>
