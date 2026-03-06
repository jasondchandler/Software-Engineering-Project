<?php
    include "connect.php";

    $id = $_POST["meeting_id"];
    $date = $_POST["date"];
    $time = $_POST["time"];
    $location = $_POST["location"];
    $duration = $_POST["duration"];
    $notes = $_POST["notes"];
    $datetime = $date . " " . $time; 

    $start_time = new DateTime($datetime);
    $end_time = clone $start_time;
    $end_time = $end_time->modify("+{$duration} minutes");

    $start_time = $start_time->format("Y-m-d H:i:s");
    $end_time = $end_time->format("Y-m-d H:i:s");
   
    $sql = "UPDATE MEETINGS 
            SET location=?, duration=?, notes=?
            WHERE meeting_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sisi", $location, $duration, $notes, $id);
    $stmt->execute();

    $sql = "UPDATE MEETING_TIMES
            SET  start_time=?, end_time=?
            WHERE meeting_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $start_time, $end_time, $id);
    $stmt->execute();

    header("Location: meetings.php");
    exit();

?>