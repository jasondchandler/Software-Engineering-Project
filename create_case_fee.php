<?php
session_start();
require_once("connect.php");

if (empty($_SESSION["user_id"])) {
    $_SESSION["login_error"] = "Please log in.";
    header("Location: login.php");
    exit;
}

if ($_SESSION["role"] !== "admin") {
    $_SESSION["case_error"] = "Only the attorney/admin can add case fees.";
    header("Location: cases.php");
    exit;
}

$case_id = (int) $_POST["case_id"];
$fee_type = trim($_POST["fee_type"]);
$description = trim($_POST["description"]);
$amount = trim($_POST["amount"]);
$date_charged = trim($_POST["date_charged"]);

if ($case_id <= 0 || $fee_type === "" || $amount === "") {
    $_SESSION["case_error"] = "Please fill in all required fee fields.";
    header("Location: cases.php");
    exit;
}

if (!is_numeric($amount) || (float)$amount < 0) {
    $_SESSION["case_error"] = "Amount must be a valid number.";
    header("Location: cases.php");
    exit;
}

if ($description === "") {
    $description = NULL;
}

if ($date_charged === "") {
    $date_charged = NULL;
}

$stmt = $conn->prepare("
    INSERT INTO case_fee (case_id, fee_type, description, amount, date_charged)
    VALUES (?, ?, ?, ?, ?)
");

$stmt->bind_param("issds", $case_id, $fee_type, $description, $amount, $date_charged);

if ($stmt->execute()) {
    $_SESSION["case_success"] = "Fee added successfully.";
} else {
    $_SESSION["case_error"] = "Failed to add fee.";
}

$stmt->close();
$conn->close();

header("Location: cases.php");
exit;
?>