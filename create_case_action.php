<?php
include "connect.php";

$title = $_POST["title"];
$court = $_POST["court"];
$type = $_POST["type"];
$filing_date = $_POST["filing_date"];
$status = $_POST["status"];

$sql = "INSERT INTO CASES (title, court, type, filing_date, status) 
        VALUES (?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssss", $title, $court, $type, $filing_date, $status);

if ($stmt->execute()) {
    echo "Case added successfully!";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
header("Location: cases.php");
exit;
?>