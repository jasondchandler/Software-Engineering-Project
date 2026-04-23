<?php 
session_start();
include "connect.php";
 $chat_id = $_POST['chat_id'];
    $sender_id = $_SESSION['user_id'];
    $message = trim($_POST['message']);

    if ($message === '') {
        die("Message cannot be empty.");
    }

    $stmt = $conn->prepare("
        INSERT INTO CHAT_MESSAGES (chat_id, sender_id, message)
        VALUES (?, ?, ?)
    ");

    $stmt->bind_param("iis", $chat_id, $sender_id, $message);
    $stmt->execute();
    $stmt->close();

   header("Location: chat_show.php?chat_id=$chat_id");
    exit();

?>