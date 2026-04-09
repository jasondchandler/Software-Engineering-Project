<?php
session_start();
require_once("connect.php");

$case_id = $_POST['document_id'] ?? null;

if (!$case_id) {
    die("No case ID provided.");
}

$stmt = $conn->prepare("DELETE FROM documents WHERE document_id = ?");
$stmt->bind_param("i", $case_id);
$stmt->execute();
$_SESSION["delete_document_msg"] = "Case deleted successfully.";

header("Location: documents.php");
exit;
?>