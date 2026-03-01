<?php
    include "connection.php";

    $id = $_POST["meeting_id"];
    $date = $_POST["date"];
    $time = $_POST["time"];
    $location = $_POST["location"];
    $notes = $_POST["notes"];

    $sql = "INSERT INTO MEETINGS (date, time, location, duration, notes)
            VALUES (?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $date, $time, $location, $duration, $notes);

    $stmt->execute();

    header("Location: meetings.php");
    exit();
?>