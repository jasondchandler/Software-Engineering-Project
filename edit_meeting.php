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

    $string_start_time = $start_time->format("Y-m-d H:i:s");
    $string_end_time = $end_time->format("Y-m-d H:i:s");

    $buffer_mins = 8;
    $buffered_start = clone $start_time;
    $buffered_start->modify("-{$buffer_mins} minutes");
    $buffered_end = clone $end_time;
    $buffered_end->modify("+{$buffer_mins} minutes");  
    $buffered_start = $buffered_start->format("Y-m-d H:i:s");  
    $buffered_end = $buffered_end->format("Y-m-d H:i:s");

    // check for times that are already booked
    $sql = "select count(*) AS time_conflicts
        from meeting_times mt
        join meetings m ON m.meeting_id = mt.meeting_id
        where m.status != 'cancelled'
        and (
        mt.start_time < ? AND mt.end_time > ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $buffered_end, $buffered_start);
        $stmt->execute();
        $result = $stmt->get_result();
        $time_conflicts = $result->fetch_assoc()["time_conflicts"];

        if ($time_conflicts > 0) {
           $_SESSION["edit_meeting_error"] = "There is a time conflict. Select a new time or date.";
           $_SESSION["edit_meeting_id"];
           header("Location: meetings.php");
           exit;
        }

    // check unavailable times
    $stmt = $conn->prepare("
    SELECT * FROM unavailable_times
    WHERE (repeat_daily = 1 OR date = ?)
      AND (? < end_time AND ? > start_time)");

    $buffered_start_time = (new DateTime($buffered_start))->format("H:i:s");
    $buffered_end_time = (new DateTime($buffered_end))->format("H:i:s");
    $stmt->bind_param("sss", $date, $buffered_start_time, $buffered_end_time);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION["edit_meeting_error"] = "This time is unavailable. Select a new time or date.";
        $_SESSION["edit_meeting_id"];
        header("Location: meetings.php");
        exit;
    }
   
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
    $stmt->bind_param("ssi", $string_start_time, $string_end_time, $id);
    $stmt->execute();

    header("Location: meetings.php");
    exit();

?>