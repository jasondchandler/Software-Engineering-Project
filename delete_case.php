<?php
session_start();
require_once("connect.php");

$case_id = $_GET['case_id'] ?? null;

if (!$case_id) {
    die("No case ID provided.");
}

$stmt = $conn->prepare("DELETE FROM cases WHERE case_id = ?");
$stmt->bind_param("i", $case_id);
$stmt->execute();

header("Location: cases.php");
exit;
?>