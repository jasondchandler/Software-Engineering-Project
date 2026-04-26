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
    <link rel="icon" type="image/x-icon" href="files/icon.png">
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</head>

<body>
<?php include "nav.php"; ?>

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
if ($_SESSION["role"] !== "admin") {
    $r1 = $conn->query("SELECT COUNT(*) AS c FROM cases WHERE status='pending' AND user_id=" . (int)$_SESSION['user_id']);
}
$pendingCount = $r1 ? (int)$r1->fetch_assoc()["c"] : 0;

$r2 = $conn->query("SELECT COUNT(*) AS c FROM cases WHERE status='open'");
if ($_SESSION["role"] !== "admin") {
    $r2 = $conn->query("SELECT COUNT(*) AS c FROM cases WHERE status='open' AND user_id=" . (int)$_SESSION['user_id']);
}
$ongoingCount = $r2 ? (int)$r2->fetch_assoc()["c"] : 0;

$r3 = $conn->query("SELECT COUNT(*) AS c FROM cases WHERE status='closed'");
if ($_SESSION["role"] !== "admin") {
    $r3 = $conn->query("SELECT COUNT(*) AS c FROM cases WHERE status='closed' AND user_id=" . (int)$_SESSION['user_id']);
}
$closedCount = $r3 ? (int)$r3->fetch_assoc()["c"] : 0;

$show_edit_modal = !empty($_SESSION["edit_case_error"]);
?>

<div class="container mb-1">
    <div class="nav">
        <div>
            <h1>Case Dashboard</h1>
            <div class="small">Welcome back, <?php echo h($_SESSION["name"]); ?></div>
        </div>
    </div>

    <div class="stats mb-3">
        <div class="stat text-center">
            <div class="small">Open Cases</div>
            <div class="num fs-4"><?php echo $ongoingCount; ?></div>
            <div class="small">In progress</div>
        </div>    
    
        <div class="stat text-center">
            <div class="small">Pending Cases</div>
            <div class="num fs-4"><?php echo $pendingCount; ?></div>
            <div class="small">Awaiting review</div>
        </div>

        <div class="stat text-center">
            <div class="small">Closed Cases</div>
            <div class="num fs-4"><?php echo $closedCount; ?></div>
            <div class="small">Completed</div>
        </div>
    </div>

    <?php if (allow('create-case')): ?>
        <button type="button" class="btn btn-dark w-100 mb-3 mt-0" data-bs-toggle="modal" data-bs-target="#createCaseForm"> 
            Create Case
        </button>
    <?php endif; ?>

    <div class="modal fade" id="createCaseForm" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Create Case</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="create_case_action.php" method="POST">
                                    <label class="form-label">Case Title:</label><br>
                                    <input class="form-control mb-3" type="text" name="title" maxlength="50" required>

                                    <label class="form-label">Court:</label><br>
                                    <input class="form-control mb-3" type="text" name="court" maxlength="50" required>

                                    <label class="form-label">Optionally add a user:</label>
                                    <select name="user_id" class="form-control mb-3">
                                    <option value="none">None</option>
                                    <?php
                                    $usersResult = $conn->query("SELECT user_id, firstname, lastname, email, role FROM users WHERE role IN ('client', 'paralegal')");
                                    while ($userRow = $usersResult->fetch_assoc()) {
                                    echo '<option value="' . $userRow["user_id"] . '">'
                                    . h($userRow["firstname"] . ' ' . $userRow["lastname"] . ' | ' . $userRow["email"] . ' | ' . $userRow["role"])
                                    . '</option>';
                                    }
                                    ?>
                                    </select>

                                    <label class="form-label">Case Type:</label><br>
                                    <input class="form-control mb-3" type="text" name="type" maxlength="20" required>

                                    <label class="form-label">Filing Date:</label><br>
                                    <input class="form-control mb-3" type="date" name="filing_date" required>

                                    <label class="form-label">Status:</label><br>
                                    <select class="form-control mb-3" name="status" required>
                                        <option value="">--Select Status--</option>
                                        <option value="Open">Open</option>
                                        <option value="Closed">Closed</option>
                                        <option value="Pending">Pending</option>
                                        <option value="Appeal">Appeal</option>
                                    </select>

                                    <div class="mb-3 form-check">
                                        <input type="checkbox" class="form-check-input" id="open_retainer" name="open_retainer" value="1">
                                        <label class="form-check-label" for="open_retainer">
                                            Open Retainer ($3,000)
                                        </label>
                                </div>

                                    <button type="submit" class="btn btn-primary form-control">Create Case</button>
                                </form>
                        </div>
                    </div>
                </div>
            </div>

    <div class="search_container"> 
        <form class="mb-2" method="GET">
            <input class="form-control mb-3" type="text" name="search" placeholder="Enter search term..." value="<?php echo h($_GET['search'] ?? ''); ?>">
            <button class="btn btn-primary w-100">Search</button>
        </form> 

        <a class="btn btn-primary w-100 mb-3" href="<?= strtok($_SERVER['REQUEST_URI'], '?'); ?>">Clear Search</a>
    
        <?php
        if (!empty($_SESSION["create_case_error"])) {
            echo '<div class="alert alert-danger text-center mt-3" role="alert">';
            echo h($_SESSION["create_case_error"]);
            echo '</div>';
            unset($_SESSION["create_case_error"]);
        }

        if (!empty($_SESSION["create_case_success"])) {
            echo '<div class="alert alert-success text-center mt-3" role="alert">';
            echo h($_SESSION["create_case_success"]);
            echo '</div>';
            unset($_SESSION["create_case_success"]);
        }

        if (!empty($_SESSION["delete_case_msg"])) {
            echo '<div class="alert alert-success text-center mt-3" role="alert">';
            echo h($_SESSION["delete_case_msg"]);
            echo '</div>';
            unset($_SESSION["delete_case_msg"]);
        }

        if (!empty($_SESSION["case_error"])) {
            echo '<div class="alert alert-danger text-center mt-3" role="alert">';
            echo h($_SESSION["case_error"]);
            echo '</div>';
            unset($_SESSION["case_error"]);
        }

        if (!empty($_SESSION["case_success"])) {
            echo '<div class="alert alert-success text-center mt-3" role="alert">';
            echo h($_SESSION["case_success"]);
            echo '</div>';
            unset($_SESSION["case_success"]);
        }

        if (!empty($_SESSION["edit_case_error"])) {
            echo '<div class="alert alert-danger text-center mt-3" role="alert">';
            echo h($_SESSION["edit_case_error"]);
            echo '</div>';
            unset($_SESSION["edit_case_error"]);
        }
        ?>

        <hr>
        <br>
        <h1>Your cases:</h1>

        <div class="search_container">
            <form method="GET" action="">
                <?php if (!empty($_GET['search'])): ?>
                    <input type="hidden" name="search" value="<?php echo h($_GET['search']); ?>">
                <?php endif; ?>

                <label>
                    <input type="checkbox" name="status" value="1" <?php if(!empty($_GET['status'])) echo 'checked'; ?>> Status
                </label>
                <label>
                    <input type="checkbox" name="type" value="1" <?php if(!empty($_GET['type'])) echo 'checked'; ?>> Type
                </label>
                <button type="submit" class="btn btn-primary w-100 mt-2">Sort cases</button>
            </form>
        </div>
    </div>

    <?php
    $sql = "SELECT c.*, u.firstname, u.lastname
            FROM cases c
            LEFT JOIN users u ON c.user_id = u.user_id";

    $params = [];
    $types = "";

    $search = $_GET['search'] ?? "";
    $like = "%$search%";

    if ($_SESSION['role'] === 'admin') {
        if ($search !== "") {
            $sql .= " WHERE c.title LIKE ? OR u.firstname LIKE ? OR u.lastname LIKE ?";
            $params[] = $like;
            $params[] = $like;
            $params[] = $like;
            $types = "sss";
        }
    } else {
        if ($search !== "") {
            $sql .= " WHERE c.user_id = ? AND c.title LIKE ?";
            $params[] = (int)$_SESSION['user_id'];
            $params[] = $like;
            $types = "is"; 
        } else {
            $sql .= " WHERE c.user_id = ?";
            $params[] = (int)$_SESSION['user_id'];
            $types = "i";
        }
    }

    $sql .= " ORDER BY ";

    if (isset($_GET["status"]) && isset($_GET["type"])) {
        $sql .= "c.status, c.type";
    } elseif (isset($_GET["status"])) {
        $sql .= "c.status, c.filing_date";
    } elseif (isset($_GET["type"])) {
        $sql .= "c.type, c.filing_date";
    } else {
        $sql .= "c.filing_date";
    }

    $stmt = $conn->prepare($sql);

    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    if($result && $result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $feeStmt = $conn->prepare("
                SELECT COALESCE(SUM(amount), 0) AS total_fees
                FROM case_fee
                WHERE case_id = ?
            ");
            $feeStmt->bind_param("i", $row["case_id"]);
            $feeStmt->execute();
            $feeResult = $feeStmt->get_result();
            $feeRow = $feeResult->fetch_assoc();
            $totalFees = $feeRow ? $feeRow["total_fees"] : 0;
            $feeStmt->close();

            echo "<div class='meeting'>";
            echo "<span class='name'>Title: ".h($row['title'])."</span><br>";
            echo "<span>User: ".($row['firstname'] ? h($row['firstname']." ".$row['lastname']) : "Unassigned")."</span><br>";
            echo "<span>Court: ".h($row['court'])."</span><br>";
            echo "<span>Type: ".h($row['type'])."</span><br>";
            echo "<span>Filing date: ".h($row['filing_date'])."</span><br>";
            echo "<span>Status: ".h($row['status'])."</span><br>";
            echo "<span>Total Fees: $".number_format((float)$totalFees, 2)."</span><br><hr>";


            $retainerStmt = $conn->prepare("
                SELECT value FROM case_retainers WHERE case_id = ?
            ");
            $retainerStmt->bind_param("i", $row["case_id"]);
            $retainerStmt->execute();
            $retainerResult = $retainerStmt->get_result();
            $retainerRow = $retainerResult->fetch_assoc();
            $retainerStmt->close();

            $retainerValue = $retainerRow ? (float)$retainerRow["value"] : 0;


            $totalHoursStmt = $conn->prepare("
                SELECT COALESCE(SUM(hours), 0) AS total_hours
                FROM case_hours
                WHERE case_id = ?
            ");
            $totalHoursStmt->bind_param("i", $row["case_id"]);
            $totalHoursStmt->execute();
            $totalHoursResult = $totalHoursStmt->get_result();
            $totalHoursRow = $totalHoursResult->fetch_assoc();
            $totalHoursStmt->close();

            $totalHours = (float)$totalHoursRow["total_hours"];

            $hourlyRate = 100;
            $hoursCost = $totalHours * $hourlyRate;
            $remainingRetainer = $retainerValue - $totalFees - $hoursCost;
            ?>

            <?php if ($retainerValue > 0): ?>
                <span>Retainer: $<?php echo number_format($retainerValue, 2); ?></span><br>
                <span>Fees Used: $<?php echo number_format($totalFees, 2); ?></span><br>
                <span>Hours Used: $<?php echo number_format($hoursCost, 2); ?></span><br>
                <span><strong>Remaining Retainer: $<?php echo number_format($remainingRetainer, 2); ?></strong></span><br>
            <?php else: ?>
                <span>No retainer opened</span><br>
            <?php endif;

            $hoursStmt = $conn->prepare("
                SELECT work_date, hours, description
                FROM case_hours
                WHERE case_id = ?
                ORDER BY work_date DESC
            ");
            $hoursStmt->bind_param("i", $row["case_id"]);
            $hoursStmt->execute();
            $hoursResult = $hoursStmt->get_result();
            ?>

            <span>Logged Hours:</span><br>

            <?php if ($hoursResult->num_rows > 0): ?>
            <?php while ($hour = $hoursResult->fetch_assoc()): ?>
            <span>
                - <?php echo h($hour["work_date"]); ?> |
                <?php echo h($hour["hours"]); ?> hrs |
                <?php echo h($hour["description"]); ?>
            </span><br>
            <?php endwhile; ?>
            <?php else: ?>
            <span>- No hours logged</span><br>
            <?php endif; ?>

            <?php $hoursStmt->close(); 

            echo "<div class='d-flex gap-2 flex-wrap'>";
            if (allow("edit-case")) {
                echo '<button class="btn btn-warning flex-fill" data-bs-toggle="modal" data-bs-target="#editCaseForm' . $row['case_id'] . '">Edit</button>';
            }
            if (allow("delete-case")) {
                echo '<button class="btn btn-danger flex-fill" data-bs-toggle="modal" data-bs-target="#deleteCaseForm' . $row['case_id'] . '">Delete</button>';
            }
            if ($_SESSION["role"] === "admin") {
                echo '<button class="btn btn-dark flex-fill" data-bs-toggle="modal" data-bs-target="#addFee' . $row['case_id'] . '">Add Fee</button>';
            }
            if ($_SESSION["role"] === "admin") {
                echo '<button class="btn btn-secondary flex-fill" data-bs-toggle="modal" data-bs-target="#logHours' . $row['case_id'] . '">
                Log Hours </button>';
            }
            echo "</div>";
            echo "</div>";
            ?>


            <div class="modal fade" id="editCaseForm<?= $row['case_id'] ?>" 
                 data-edit-modal="<?php echo $show_edit_modal ? 'true' : 'false'; ?>" 
                 tabindex="-1" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title">Edit Case</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <form action="update_case_action.php" method="POST">
                        <input type="hidden" name="case_id" value="<?php echo $row['case_id']; ?>">

                        <label>Title:</label>
                        <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($row['title']); ?>" required><br>

                        <label>Court:</label>
                        <input type="text" name="court" class="form-control" value="<?php echo htmlspecialchars($row['court']); ?>" required><br>

                        <label>Type:</label>
                        <input type="text" name="type" class="form-control" value="<?php echo htmlspecialchars($row['type']); ?>" required><br>

                        <label>Filing Date:</label>
                        <input type="date" name="filing_date" class="form-control" value="<?php echo $row['filing_date']; ?>" required><br>

                        <label>Status:</label>
                        <select name="status" class="form-control" required>
                            <option value="Open" <?php if($row['status']=='Open') echo 'selected'; ?>>Open</option>
                            <option value="Closed" <?php if($row['status']=='Closed') echo 'selected'; ?>>Closed</option>
                            <option value="Pending" <?php if($row['status']=='Pending') echo 'selected'; ?>>Pending</option>
                            <option value="Appeal" <?php if($row['status']=='Appeal') echo 'selected'; ?>>Appeal</option>
                        </select><br>


                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-fill">Update Case</button>
                            <a href="cases.php" class="btn btn-dark flex-fill">Cancel</a>
                        </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>

            <div class="modal fade" id="deleteCaseForm<?= $row['case_id'] ?>" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title">Delete Case</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>

                  <div class="modal-body">
                    Are you sure you want to delete this case?
                  </div>

                  <div class="modal-footer">
                    <form action="delete_case_action.php" method="POST">
                        <input type="hidden" name="case_id" value="<?= $row['case_id'] ?>">
                        <button type="submit" class="btn btn-success">Yes</button>
                    </form>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">No</button>
                  </div>
                </div>
              </div>
            </div>

            <?php if ($_SESSION["role"] === "admin"): ?>
            <div class="modal fade" id="addFee<?= $row['case_id'] ?>" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title">Add Fee</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>

                  <div class="modal-body">
                    <form action="create_case_fee.php" method="POST">
                        <input type="hidden" name="case_id" value="<?= $row['case_id'] ?>">

                        <label>Fee Type:</label>
                        <input type="text" name="fee_type" class="form-control" required><br>

                        <label>Description:</label>
                        <textarea name="description" class="form-control"></textarea><br>

                        <label>Amount:</label>
                        <input type="number" name="amount" class="form-control" step="0.01" min="0" required><br>

                        <label>Date Charged:</label>
                        <input type="date" name="date_charged" class="form-control"><br>

                        <button type="submit" class="btn btn-primary form-control">Add Fee</button>
                        
                    </form>
                  </div>
                </div>
              </div>
            </div>
            
                <div class="modal fade" id="logHours<?= $row['case_id'] ?>" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">

                            <div class="modal-header">
                                <h5 class="modal-title">Log Case Hours</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">
                                <form action="create_case_hours.php" method="POST">

                                    <input type="hidden" name="case_id" value="<?= $row['case_id'] ?>">

                                    <label>Work Date:</label>
                                    <input type="date" name="work_date" class="form-control mb-2" required>

                                    <label>Hours:</label>
                                    <input type="number" name="hours" class="form-control mb-2" step="0.25" min="0.25" required>

                                    <label>Description:</label>
                                    <textarea name="description" class="form-control mb-2" required></textarea>

                                    <button type="submit" class="btn btn-primary w-100">Log Hours</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php
        }
    } else {
        echo "<p>No cases found.</p>";
    }

    $stmt->close();
    $conn->close();
    ?>
</div>
</body>
</html>
