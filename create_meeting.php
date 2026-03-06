<?php
    include "connect.php";
    session_start();

    $date = $_POST["date"];
    $time = $_POST["time"];
    $location = $_POST["location"];
    $duration = $_POST["duration"];
    $notes = $_POST["notes"];
    $user_id = $_SESSION["user_id"];
    $datetime = $date . " " . $time; 

    $start_time = new DateTime($datetime);
    $end_time = clone $start_time;
    $end_time = $end_time->modify("+{$duration} minutes");

    $start_time = $start_time->format("Y-m-d H:i:s");
    $end_time = $end_time->format("Y-m-d H:i:s");
   
    $sql = "INSERT INTO MEETINGS (location, duration, notes, user_id)
            VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $location, $duration, $notes, $user_id);
    $stmt->execute();

    $meeting_id = $conn->insert_id;
    $sql = "INSERT INTO MEETING_TIMES (meeting_id, start_time, end_time)
            VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $meeting_id, $start_time, $end_time);
    $stmt->execute();

    header("Location: meetings.php");
    exit();
?>