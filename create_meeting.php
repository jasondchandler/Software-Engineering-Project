<?php
    include "connect.php";
    session_start();

    $date = $_POST["date"];
    $time = $_POST["time"];
    $location = $_POST["location"];
    $duration = $_POST["duration"];
    $notes = $_POST["notes"];
    $user_id = $_SESSION["user_id"];
   

    $sql = "INSERT INTO MEETINGS (meeting_date, meeting_time, location, duration, notes, user_id)
            VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $date, $time, $location, $duration, $notes, $user_id);

    $stmt->execute();

    header("Location: meetings.php");
    exit();
?>