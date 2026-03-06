<?php 

    require_once("connect.php");

    $id = $_POST["unavailable_id"];

    $stmt = $conn->prepare("DELETE FROM unavailable_times WHERE times_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    header("Location: meetings.php");
    exit;
?>