<?php
    include "connection.php";

    $id = $_POST["meeting_id"];
    $date = $_POST["date"];
    $time = $_POST["time"];
    $location = $_POST["location"];
    $duration = $_POST["duration"];
    $notes = $_POST["notes"];

    $sql = "UPDATE meetings SET
            date = ?, time = ?, location = ?, 
            duration = ?, notes = ?
            WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $date, $time, $location, $duration, $notes, $id);

    $stmt->execute();

    header("Location: meetings.php");
    exit();
?>