<?php
require_once("connect.php");
session_start();

// Get form data
$email = $_POST["email"];
$password = $_POST["password"];
$password_confirm = $_POST["password_confirm"];
$firstname = $_POST["firstname"];
$lastname = $_POST["lastname"];
$phone = $_POST["phone"];
$address = $_POST["address"];

// Check required fields
if (!$password || !$password_confirm || !$firstname || !$lastname || !$email) {
    $_SESSION["signup_error"] = "Please fill in all required fields.";
    header("Location: signup.php");
}

// Check password match
if ($password !== $password_confirm) {
    $_SESSION["signup_error"] = "Passwords do not match.";
    header("Location: signup.php");
}

// Check if email is unique
$stmt = $conn->prepare("select email from users where email = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $_SESSION["signup_error"] = "Email taken. Please use another.";
    header("Location: signup.php");
}

// Check if phone is unique
$stmt = $conn->prepare("select phone from users where phone = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $_SESSION["signup_error"] = "Phone number taken. Please use another.";
    header("Location: signup.php");
}

// Hash password
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// Convert empty optional fields to NULL
$phone = $phone ? $phone : NULL;
$address = $address ? $address : NULL;

// Prepare SQL statement
$stmt = $conn->prepare("INSERT INTO USERS (email, password, firstname, lastname, phone, address)
                        VALUES (?, ?, ?, ?, ?, ?)");

$stmt->bind_param("ssssss", $email, $password_hash, $firstname, $lastname, $phone, $address);

// Execute
if ($stmt->execute()) {
    $_SESSION["name"] = $firstname . $lastname;
    $stmt = $conn->prepare("select user_id from users where email = $email");
    $result = $stmt->get_result();
    $row = $result->fetch_assoc()	
    $_SESSION["user_id"] = $row["user_id"];
    header("Location: index.php");
} else {
    $_SESSION["signup_error"] = "Please try again.";
    header("Location: signup.php");
}

$stmt->close();
$conn->close();
?>