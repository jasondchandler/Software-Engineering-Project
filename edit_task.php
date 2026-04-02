<?php
session_start();
require_once("connect.php");

if (empty($_SESSION["user_id"])) {
    $_SESSION["login_error"] = "Please log in.";
    header("Location: login.php");
    exit;
}

if ($_SESSION["role"] !== "admin") {
    $_SESSION["task_error"] = "Only the attorney/admin can edit tasks.";
    header("Location: tasks.php");
    exit;
}

$task_id = (int) $_POST["task_id"];
$user_id = (int) $_POST["user_id"];
$description = trim($_POST["description"]);
$can_complete_digitally = isset($_POST["can_complete_digitally"]) ? 1 : 0;
$status = trim($_POST["status"]);

$allowed_statuses = ["Pending", "Completed"];

if ($task_id <= 0 || $user_id <= 0 || $description === "") {
    $_SESSION["task_error"] = "Please fill in all required fields.";
    header("Location: tasks.php");
    exit;
}

if (!in_array($status, $allowed_statuses, true)) {
    $_SESSION["task_error"] = "Invalid task status.";
    header("Location: tasks.php");
    exit;
}

if ($status === "Pending") {
    $sql = "UPDATE tasks
            SET user_id = ?,
                description = ?,
                can_complete_digitally = ?,
                status = ?,
                completion_notes = NULL,
                completion_file = NULL,
                completed_at = NULL
            WHERE task_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isissi", $user_id, $description, $can_complete_digitally, $status, $task_id);
} else {
    $sql = "UPDATE tasks
            SET user_id = ?,
                description = ?,
                can_complete_digitally = ?,
                status = ?,
                completed_at = COALESCE(completed_at, CURRENT_TIMESTAMP)
            WHERE task_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isissi", $user_id, $description, $can_complete_digitally, $status, $task_id);
}

if ($stmt->execute()) {
    $_SESSION["task_success"] = "Task updated successfully.";
} else {
    $_SESSION["task_error"] = "Failed to update task.";
}

$stmt->close();
$conn->close();

header("Location: tasks.php");
exit;
?>