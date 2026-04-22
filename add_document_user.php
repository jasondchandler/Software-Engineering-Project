<?php
session_start();
require_once("connect.php");

if (empty($_SESSION["user_id"])) {
    $_SESSION["login_error"] = "Please log in.";
    header("Location: login.php");
    exit;
}

if ($_SESSION["role"] !== "admin") {
    $_SESSION["document_error"] = "Only the attorney/admin can grant document access.";
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

$stmt = $conn->prepare("SELECT * FROM document_users WHERE document_id = ? AND user_id = ?");
$stmt->bind_param("ii", $document_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

if ($result->num_rows > 0) {
    $_SESSION["document_error"] = "That user already has access to this document.";
    header("Location: documents.php");
    exit;
}

$stmt = $conn->prepare("INSERT INTO document_users (document_id, user_id) VALUES (?, ?)");
$stmt->bind_param("ii", $document_id, $user_id);

if ($stmt->execute()) {
    $_SESSION["document_success"] = "Document access granted.";
} else {
    $_SESSION["document_error"] = "Failed to grant document access.";
}

$stmt->close();
$conn->close();

header("Location: documents.php");
exit;
?>