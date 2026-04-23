<?php
session_start();
require_once("connect.php");

if (empty($_SESSION["user_id"])) {
    $_SESSION["login_error"] = "Please log in.";
    header("Location: login.php");
    exit;
}

if ($_SESSION["role"] !== "admin") {
    $_SESSION["task_error"] = "Only the attorney/admin can assign tasks.";
    header("Location: tasks.php");
    exit;
}

$user_id = $_POST["user_id"];
$description = trim($_POST["description"]);
$date = $_POST["date"];
$time = $_POST["time"];
$datetime = $date . ' ' . $time . ':00';
$can_complete_digitally = isset($_POST["can_complete_digitally"]) ? 1 : 0;

if (empty($user_id) || empty($description)) {
    $_SESSION["task_error"] = "Please fill in all required fields.";
    header("Location: tasks.php");
    exit;
}

$sql = "INSERT INTO TASKS (user_id, description, can_complete_digitally, due)
        VALUES (?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("isis", $user_id, $description, $can_complete_digitally, $datetime);

if ($stmt->execute()) {
    $_SESSION["task_success"] = "Task assigned successfully.";
} else {
    $_SESSION["task_error"] = "Failed to assign task.";
}

$stmt->close();
$conn->close();

header("Location: tasks.php");
exit;
?>