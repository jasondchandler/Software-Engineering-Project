<?php
session_start();
require_once("connect.php");

if (empty($_SESSION["user_id"])) {
    $_SESSION["login_error"] = "Please log in.";
    header("Location: login.php");
    exit;
}

if ($_SESSION["role"] !== "admin") {
    $_SESSION["document_error"] = "Only the attorney/admin can remove document access.";
    header("Location: documents.php");
    exit;
}

$document_id = (int) $_POST["document_id"];
$user_id = (int) $_POST["user_id"];

if ($document_id <= 0 || $user_id <= 0) {
    $_SESSION["document_error"] = "Invalid document or user.";
    header("Location: documents.php");
    exit;
}

$stmt = $conn->prepare("DELETE FROM document_users WHERE document_id = ? AND user_id = ?");
$stmt->bind_param("ii", $document_id, $user_id);

if ($stmt->execute()) {
    $_SESSION["document_success"] = "Document access removed.";
} else {
    $_SESSION["document_error"] = "Failed to remove document access.";
}

$stmt->close();
$conn->close();

header("Location: documents.php");
exit;
?>