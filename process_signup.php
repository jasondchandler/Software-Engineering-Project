<?php
require_once("connect.php");

// Get form data
$username = $_POST["username"];
$password = $_POST["password"];
$password_confirm = $_POST["password_confirm"];
$firstname = $_POST["firstname"];
$lastname = $_POST["lastname"];
$email = $_POST["email"];
$phone = $_POST["phone"];
$address = $_POST["address"];

// Check required fields
if (!$username || !$password || !$password_confirm || !$firstname || !$lastname || !$email) {
    die("Please fill in all required fields.");
}

// Check password match
if ($password !== $password_confirm) {
    die("Passwords do not match.");
}

// Hash password
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// Convert empty optional fields to NULL
$phone = $phone ? $phone : NULL;
$address = $address ? $address : NULL;

// Prepare SQL statement
$stmt = $conn->prepare("INSERT INTO USERS (username, password, firstname, lastname, email, phone, address)
                        VALUES (?, ?, ?, ?, ?, ?, ?)");

$stmt->bind_param("sssssss", $username, $password_hash, $firstname, $lastname, $email, $phone, $address);

// Execute
if ($stmt->execute()) {
    echo "Account created successfully!";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>