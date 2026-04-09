<?php
session_start();
include "connect.php";

$title = $_POST["title"];
$court = $_POST["court"];
$type = $_POST["type"];
$filing_date = $_POST["filing_date"];
$status = $_POST["status"];
$user_id = $_POST["user_id"];

if ($user_id === "none") {
$sql = "INSERT INTO CASES (title, court, type, filing_date, status) 
        VALUES (?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssss", $title, $court, $type, $filing_date, $status);
}
else {
$sql = "INSERT INTO CASES (user_id, title, court, type, filing_date, status) 
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("isssss", $user_id, $title, $court, $type, $filing_date, $status);


}

if ($stmt->execute()) {
    echo "Case added successfully!";
    $_SESSION["create_case_success"] = "Case created successfully.";
} else {
    echo "Error: " . $stmt->error;
    $_SESSION["create_case_error"] = "Case not created.";
}

$stmt->close();
$conn->close();
header("Location: cases.php");
exit;
?>