<?php
session_start();
require_once("connect.php");

if (empty($_SESSION["user_id"])) {
    $_SESSION["login_error"] = "Please log in.";
    header("Location: login.php");
    exit;
}

if ($_SESSION["role"] !== "admin") {
    $_SESSION["case_error"] = "Only the attorney/admin can log case hours.";
    header("Location: cases.php");
    exit;
}

$case_id = (int) $_POST["case_id"];
$work_date = $_POST["work_date"];
$hours = trim($_POST["hours"]);
$description = trim($_POST["description"]);

if ($case_id <= 0 || empty($work_date) || empty($hours) || empty($description)) {
    $_SESSION["case_error"] = "Please fill in all hour fields.";
    header("Location: cases.php");
    exit;
}

if (!is_numeric($hours) || (float)$hours <= 0) {
    $_SESSION["case_error"] = "Hours must be a valid number.";
    header("Location: cases.php");
    exit;
}

$stmt = $conn->prepare("
    INSERT INTO case_hours (case_id, work_date, hours, description)
    VALUES (?, ?, ?, ?)
");

$stmt->bind_param("isds", $case_id, $work_date, $hours, $description);

if ($stmt->execute()) {
    $_SESSION["case_success"] = "Hours logged successfully.";
} else {
    $_SESSION["case_error"] = "Failed to log hours.";
}

$stmt->close();
$conn->close();

header("Location: cases.php");
exit;
?>