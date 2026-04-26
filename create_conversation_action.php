<?php
session_start();
include "connect.php";

$user1 = $_POST["user1"];
$user2 = $_POST["user2"];

if ($user1 === $user2) {

    $_SESSION["create_error"] = "Select two different users.";
    header("Location: messages.php");
    exit;
}
$stmt = $conn->prepare("INSERT INTO conversations () VALUES ()"); 
$stmt->execute();
$conversation_id = $conn->insert_id;
$sql = "INSERT INTO conversation_users (conversation_id, user_id) VALUES (?, ?)";

$stmt = $conn->prepare($sql);

$stmt->bind_param("ii", $conversation_id, $user1);
$stmt->execute();
$stmt->bind_param("ii", $conversation_id, $user2);

$stmt->execute();
$stmt->close();

$_SESSION["create_success"] = "Conversation created successfully.";

    header("Location: messages.php");
    exit;

?>