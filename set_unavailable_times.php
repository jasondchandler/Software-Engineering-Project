<?php 
    require_once("connect.php");
    $date = $_POST["date"];
    $start = $_POST["start_time"];
    $end = $_POST["end_time"];

    $repeat = isset($_POST["repeat"]) ? 1 : 0;

    $stmt = $conn->prepare("insert into unavailable_times (date, start_time, end_time, repeat_daily) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $date, $start, $end, $repeat);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    header("Location: meetings.php");
    $conn->close();
    $stmt->close();
?>