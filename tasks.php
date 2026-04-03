<?php 
  session_start(); 
  require_once("connect.php");
?>
<head>

    <title>Charles Casale - Tasks</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
      
    <script src="site.js" defer></script>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/x-icon" href="">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" 
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" 
    crossorigin="anonymous"></script>

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

function h($s){ 
    return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); 
}

$taskCount = 0;

if ($_SESSION["role"] === "admin") {
    $r1 = $conn->query("SELECT COUNT(*) AS c FROM tasks WHERE status = 'Pending'");
    if ($r1) {
        $taskCount = (int)$r1->fetch_assoc()["c"];
    }
} else {
    $stmtCount = $conn->prepare("SELECT COUNT(*) AS c FROM tasks WHERE status = 'Pending' AND user_id = ?");
    $stmtCount->bind_param("i", $_SESSION["user_id"]);
    $stmtCount->execute();
    $resCount = $stmtCount->get_result();
    if ($resCount) {
        $taskCount = (int)$resCount->fetch_assoc()["c"];
    }
    $stmtCount->close();
}
?>

<div class="container">

    <div class="nav">
      <div>
        <h1>Task Dashboard</h1>
        <div class="small">Welcome back, <?php echo h($_SESSION["name"]); ?></div>
      </div>
    </div>

    <div class="stats">
      <div class="stat">
        <div class="small">Pending Tasks</div>
        <div class="num"><?php echo $taskCount; ?></div>
        <div class="small">Awaiting completion</div>
      </div>
    </div>

    <?php
    if (!empty($_SESSION["task_error"])) {
        echo '<div class="alert alert-danger text-center mt-3" role="alert">';
        echo h($_SESSION["task_error"]);
        echo '</div>';
        unset($_SESSION["task_error"]);
    }

    if (!empty($_SESSION["task_success"])) {
        echo '<div class="alert alert-success text-center mt-3" role="alert">';
        echo h($_SESSION["task_success"]);
        echo '</div>';
        unset($_SESSION["task_success"]);
    }
    ?>

    <?php if ($_SESSION["role"] === "admin"): ?>
      <button type="button" class="btn btn-dark w-100 mt-3 mb-3" data-bs-toggle="modal" data-bs-target="#assignTaskForm">
        Assign Task
      </button>

      <div class="modal fade" id="assignTaskForm" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            
            <div class="modal-header">
              <h5 class="modal-title">Assign Task</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
              <form action="create_task.php" method="POST">

                <div class="mb-3">
                  <label class="form-label">Assign To:</label>
                  <select name="user_id" class="form-control" required>
                    <?php
                    $usersResult = $conn->query("SELECT user_id, firstname, lastname, email, role FROM users WHERE role IN ('client', 'paralegal')");
                    while ($userRow = $usersResult->fetch_assoc()) {
                        echo '<option value="' . $userRow["user_id"] . '">'
                            . h($userRow["firstname"] . ' ' . $userRow["lastname"] . ' | ' . $userRow["email"] . ' | ' . $userRow["role"])
                            . '</option>';
                    }
                    ?>
                  </select>
                </div>

                <div class="mb-3">
                  <label class="form-label">Task Description:</label>
                  <textarea name="description" class="form-control" required></textarea>
                </div>

                <div class="mb-3 form-check">
                  <input type="checkbox" class="form-check-input" id="digital" name="can_complete_digitally" value="1">
                  <label class="form-check-label" for="digital">
                    Can be completed digitally
                  </label>
                </div>

                <button type="submit" class="btn btn-primary form-control">Assign Task</button>
              </form>
            </div>

          </div>
        </div>
      </div>
    <?php endif; ?>

    <br><br>
    <h1>Your Tasks:</h1>

    <?php
    if ($_SESSION["role"] === "admin") {
        $sql = "SELECT t.*, u.firstname, u.lastname, u.email, u.role
                FROM tasks t
                JOIN users u ON t.user_id = u.user_id
                ORDER BY t.created_at DESC";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        $sql = "SELECT *
                FROM tasks
                WHERE user_id = ?
                ORDER BY created_at DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $_SESSION["user_id"]);
        $stmt->execute();
        $result = $stmt->get_result();
    }
    ?>

    <?php if ($result && $result->num_rows > 0): ?>
      <?php $count = 1; while ($row = $result->fetch_assoc()): ?>
        <div class="meeting">
            <span>Task #<?php echo $count; ?></span><br>
            <span>Description: <?php echo h($row["description"]); ?></span><br>
            <span>Status: <?php echo h($row["status"]); ?></span><br>
            <span>Digital Completion: <?php echo $row["can_complete_digitally"] ? "Yes" : "No"; ?></span><br>
            <span>Created: <?php echo h($row["created_at"]); ?></span><br>

            <?php if (!empty($row["completed_at"])): ?>
                <span>Completed: <?php echo h($row["completed_at"]); ?></span><br>
            <?php endif; ?>

            <?php if (!empty($row["completion_notes"])): ?>
                <span>Notes: <?php echo h($row["completion_notes"]); ?></span><br>
            <?php endif; ?>

            <?php if (!empty($row["completion_file"])): ?>
                <span>
                    File:
                    <a href="task_uploads/<?php echo rawurlencode($row["completion_file"]); ?>" target="_blank">
                        View
                    </a>
                </span><br>
            <?php endif; ?>

            <?php if ($_SESSION["role"] === "admin"): ?>
                <span>Assigned To: <?php echo h($row["firstname"] . " " . $row["lastname"] . " | " . $row["email"] . " | " . $row["role"]); ?></span><br>
            <?php endif; ?>

            <hr>

            <div class="d-flex flex-wrap gap-2">

                <?php if ($_SESSION["role"] !== "admin" && $row["status"] === "Pending" && $row["can_complete_digitally"]): ?>
                    <button class="btn btn-success flex-fill" data-bs-toggle="modal" data-bs-target="#complete<?php echo $row["task_id"]; ?>">
                        Complete Task
                    </button>
                <?php endif; ?>

                <?php if ($_SESSION["role"] === "admin"): ?>
                    <button class="btn btn-warning flex-fill" data-bs-toggle="modal" data-bs-target="#edit<?php echo $row["task_id"]; ?>">
                        Edit
                    </button>

                    <button class="btn btn-danger flex-fill" data-bs-toggle="modal" data-bs-target="#delete<?php echo $row["task_id"]; ?>">
                        Delete
                    </button>
                <?php endif; ?>

            </div>

            <?php if ($_SESSION["role"] !== "admin" && $row["status"] === "Pending" && !$row["can_complete_digitally"]): ?>
                <div class="alert alert-secondary mt-2 mb-0 text-center">
                    This task cannot be completed digitally.
                </div>
            <?php endif; ?>
        </div>

        <?php if ($_SESSION["role"] !== "admin" && $row["status"] === "Pending" && $row["can_complete_digitally"]): ?>
        <div class="modal fade" id="complete<?php echo $row["task_id"]; ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">Complete Task</h5>
                        <button class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <form action="complete_task.php" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="task_id" value="<?php echo $row["task_id"]; ?>">

                            <div class="mb-3">
                                <label class="form-label">Completion Notes:</label>
                                <textarea name="completion_notes" class="form-control" placeholder="Notes"></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Upload File (optional):</label>
                                <input type="file" name="completion_file" class="form-control">
                            </div>

                            <button class="btn btn-primary w-100">Submit</button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if ($_SESSION["role"] === "admin"): ?>
        <div class="modal fade" id="edit<?php echo $row["task_id"]; ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">Edit Task</h5>
                        <button class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <form action="edit_task.php" method="POST">
                            <input type="hidden" name="task_id" value="<?php echo $row["task_id"]; ?>">

                            <div class="mb-3">
                                <label class="form-label">Task Description:</label>
                                <textarea name="description" class="form-control"><?php echo h($row["description"]); ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Status:</label>
                                <select name="status" class="form-control">
                                    <option value="Pending" <?php echo ($row["status"] === "Pending") ? "selected" : ""; ?>>Pending</option>
                                    <option value="Completed" <?php echo ($row["status"] === "Completed") ? "selected" : ""; ?>>Completed</option>
                                </select>
                            </div>

                            <button class="btn btn-primary w-100">Update</button>
                        </form>
                    </div>

                </div>
            </div>
        </div>

        <div class="modal fade" id="delete<?php echo $row["task_id"]; ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">Delete Task</h5>
                        <button class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        Are you sure you want to delete this task?
                    </div>

                    <div class="modal-footer">
                        <form action="delete_task.php" method="POST">
                            <input type="hidden" name="task_id" value="<?php echo $row["task_id"]; ?>">
                            <button class="btn btn-success">Yes</button>
                        </form>

                        <button class="btn btn-danger" data-bs-dismiss="modal">No</button>
                    </div>

                </div>
            </div>
        </div>
        <?php endif; ?>

      <?php $count++; endwhile; ?>
    <?php else: ?>
      <p>No tasks found.</p>
    <?php endif; ?>

    <?php
    if (isset($stmt)) {
        $stmt->close();
    }
    $conn->close();
    ?>

</div>
</div>
</body>
</html>
