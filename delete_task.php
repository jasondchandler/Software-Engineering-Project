<?php
session_start();
require_once("connect.php");

if (empty($_SESSION["user_id"])) {
    $_SESSION["login_error"] = "Please log in.";
    header("Location: login.php");
    exit;
}

if ($_SESSION["role"] !== "admin") {
    $_SESSION["task_error"] = "Only the attorney/admin can delete tasks.";
    header("Location: tasks.php");
    exit;
}

$task_id = (int) $_POST["task_id"];

if ($task_id <= 0) {
    $_SESSION["task_error"] = "Invalid task.";
    header("Location: tasks.php");
    exit;
}

$stmt = $conn->prepare("SELECT completion_file FROM tasks WHERE task_id = ?");
$stmt->bind_param("i", $task_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();

if ($row && !empty($row["completion_file"])) {
    $filePath = "task_uploads/" . $row["completion_file"];
    if (file_exists($filePath)) {
        unlink($filePath);
    }
}

$stmt = $conn->prepare("DELETE FROM tasks WHERE task_id = ?");
$stmt->bind_param("i", $task_id);

if ($stmt->execute()) {
    $_SESSION["task_success"] = "Task deleted successfully.";
} else {
    $_SESSION["task_error"] = "Failed to delete task.";
}

$stmt->close();
$conn->close();

header("Location: tasks.php");
exit;
?>