<?php
session_start();
include "connect.php";

$user1 = $_POST["user1"];
$user2 = $_POST["user2"];

if ($user1 === $user2) {

    $_SESSION["create_chat_error"] = "Select two different users.";
    header("Location: chats.php");
    exit;
}
$stmt = $conn->prepare("INSERT INTO CHATS () VALUES ()"); 
$stmt->execute();
$chat_id = $conn->insert_id;
$sql = "INSERT INTO CHAT_USERS (chat_id, user_id) VALUES (?, ?)";

$stmt = $conn->prepare($sql);

$stmt->bind_param("ii", $chat_id, $user1);
$stmt->execute();
$stmt->bind_param("ii", $chat_id, $user2);

$stmt->execute();
$stmt->close();

$_SESSION["create_success"] = "Chat created successfully.";

    header("Location: chats.php");
    exit;

?>