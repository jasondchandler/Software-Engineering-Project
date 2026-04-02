<?php
session_start();
require_once("connect.php");

if (empty($_SESSION["user_id"])) {
    $_SESSION["login_error"] = "Please log in.";
    header("Location: login.php");
    exit;
}

$task_id = (int) $_POST["task_id"];
$user_id = (int) $_SESSION["user_id"];
$completion_notes = trim($_POST["completion_notes"]);


$stmt = $conn->prepare("SELECT user_id, can_complete_digitally, status FROM tasks WHERE task_id = ?");
$stmt->bind_param("i", $task_id);
$stmt->execute();
$result = $stmt->get_result();
$task = $result->fetch_assoc();
$stmt->close();


if (!$task) {
    $_SESSION["task_error"] = "Task not found.";
    header("Location: tasks.php");
    exit;
}

if ((int)$task["user_id"] !== $user_id) {
    $_SESSION["task_error"] = "You can only complete your own tasks.";
    header("Location: tasks.php");
    exit;
}

if (!(int)$task["can_complete_digitally"]) {
    $_SESSION["task_error"] = "This task cannot be completed digitally.";
    header("Location: tasks.php");
    exit;
}

if ($task["status"] === "Completed") {
    $_SESSION["task_error"] = "This task is already completed.";
    header("Location: tasks.php");
    exit;
}


$hasFile = isset($_FILES["completion_file"]) && $_FILES["completion_file"]["error"] === 0;
$hasNotes = !empty($completion_notes);

if (!$hasFile && !$hasNotes) {
    $_SESSION["task_error"] = "Please provide notes or upload a file.";
    header("Location: tasks.php");
    exit;
}

$fileName = NULL;

if ($hasFile) {
    $uploadDir = "task_uploads/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $originalName = basename($_FILES["completion_file"]["name"]);
    $extension = pathinfo($originalName, PATHINFO_EXTENSION);

    $fileName = "task_" . $task_id . "_" . time();
    if ($extension !== "") {
        $fileName .= "." . $extension;
    }

    $targetPath = $uploadDir . $fileName;

    if (!move_uploaded_file($_FILES["completion_file"]["tmp_name"], $targetPath)) {
        $_SESSION["task_error"] = "File upload failed.";
        header("Location: tasks.php");
        exit;
    }

    
    $docName = $originalName;
    $docDescription = "Task completion upload for task #" . $task_id;

    $docStmt = $conn->prepare("INSERT INTO documents (case_id, name, description, path) VALUES (NULL, ?, ?, ?)");
    $docStmt->bind_param("sss", $docName, $docDescription, $fileName);
    $docStmt->execute();
    $docStmt->close();
}

if ($completion_notes === "") {
    $completion_notes = NULL;
}


$sql = "UPDATE tasks
        SET status = 'Completed',
            completion_notes = ?,
            completion_file = ?,
            completed_at = CURRENT_TIMESTAMP
        WHERE task_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssi", $completion_notes, $fileName, $task_id);

if ($stmt->execute()) {
    $_SESSION["task_success"] = "Task completed successfully.";
} else {
    $_SESSION["task_error"] = "Failed to complete task.";
}

$stmt->close();
$conn->close();

header("Location: tasks.php");
exit;
?>
