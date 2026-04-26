<?php 


session_start();
include "connect.php";
 $conversation_id = $_POST['conversation_id'];
    $sender_id = $_SESSION['user_id'];
    $message = trim($_POST['message']);

    if ($message === '') {
        die("Message cannot be empty.");
    }

    $stmt = $conn->prepare("
        INSERT INTO conversation_messages (conversation_id, sender_id, message)
        VALUES (?, ?, ?)
    ");

    $stmt->bind_param("iis", $conversation_id, $sender_id, $message);
    $stmt->execute();
    $stmt->close();

    header("Location: conversation_show.php?conversation_id=$conversation_id");
    exit();

?>