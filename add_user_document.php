<?php 
session_start();
            require_once("connect.php");
            if (empty($_SESSION["name"])) {
                header("Location: meetings.php");
                exit;
            }
    $user_id = $_POST["user_id"];
    $document_id = $_POST["document_id"];

    $sql = "insert into document_users (document_id, user_id) values (?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $document_id, $user_id);
    $stmt->execute();

header("Location: documents.php");
            exit;

?>