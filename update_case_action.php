<?php
session_start();
require_once("connect.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $case_id = (int)$_POST['case_id'];
    $title = $conn->real_escape_string($_POST['title']);
    $court = $conn->real_escape_string($_POST['court']);
    $type = $conn->real_escape_string($_POST['type']);
    $filing_date = $conn->real_escape_string($_POST['filing_date']);
    $status = $conn->real_escape_string($_POST['status']);

    $sql = "UPDATE cases 
            SET title='$title', court='$court', type='$type', filing_date='$filing_date', status='$status' 
            WHERE case_id=$case_id";

    if ($conn->query($sql)) {
        header("Location: cases.php");
        exit;
    } else {
        echo "Error updating case: " . $conn->error;
    }
} else {
    header("Location: cases.php");
    exit;
}