<?php
    include "connect.php";
    session_start();

    $date = $_POST["date"];
    $time = $_POST["time"];
    $location = $_POST["location"];
    $duration = $_POST["duration"];
    $notes = $_POST["notes"];

        if ($_SESSION["role"] === "client") {
                $user_id = $_SESSION["user_id"];
        }  
        elseif ($_SESSION["role"] === "admin") {
                $user_id = $_POST["user_id"];
        }


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

    // Check for overlaps
    $sql = "
        select count(*) AS time_conflicts
        from meeting_times mt
        join meetings m ON m.meeting_id = mt.meeting_id
        where m.status <> 'cancelled'
        and (
        mt.start_time < ? AND mt.end_time > ?
        )";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $buffered_end, $buffered_start);
        $stmt->execute();
        $result = $stmt->get_result();
        $time_conflicts = $result->fetch_assoc()["time_conflicts"];

        if ($time_conflicts > 0) {
           $_SESSION["create_meeting_error"] = "There is a time conflict. Select a new time or date.";
           header("Location: meetings.php");
           exit;
        }


    $sql = "INSERT INTO MEETINGS (location, duration, notes, user_id)
            VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sisi", $location, $duration, $notes, $user_id);
    $stmt->execute();

    $meeting_id = $conn->insert_id;
    $sql = "INSERT INTO MEETING_TIMES (meeting_id, start_time, end_time)
            VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $meeting_id, $string_start_time, $string_end_time);
    $stmt->execute();

    header("Location: meetings.php");
    exit();
?>