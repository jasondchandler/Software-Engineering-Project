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
$_SESSION["signup_error"] = [];

// Check required fields
if (!$password || !$password_confirm || !$firstname || !$lastname || !$email) {
    $_SESSION["signup_error"][] = "Please fill in all required fields.";
}

// Check password match
if ($password !== $password_confirm) {
    $_SESSION["signup_error"][] = "Passwords do not match.";
}

// Check if email is unique
$stmt = $conn->prepare("select email from users where email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $_SESSION["signup_error"][] = "Email taken. Please use another.";
}

// Check if phone is unique
$stmt = $conn->prepare("select phone from users where phone = ?");
$stmt->bind_param("s", $phone);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $_SESSION["signup_error"][] = "Phone number taken. Please use another.";
}

if (!empty($_SESSION["signup_error"])) {
    header("Location: signup.php");
    exit;
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
    $_SESSION["name"] = $firstname . " " . $lastname;
    $stmt = $conn->prepare("select user_id from users where email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();	
    $_SESSION["user_id"] = $row["user_id"];

    // this will need to be changed
    if ($_SESSION["name"] === "charles casale") {
        $_SESSION["role"] = "admin";
    } else {
        $_SESSION["role"] = "client";
    }

	$sql = "SELECT name FROM PERMISSIONS WHERE role = ? OR role = 'all'";

	$stmt = $conn->prepare($sql);
	$stmt->bind_param("s", $_SESSION["role"]);
	$stmt->execute();

	$result = $stmt->get_result();
	$permissions = [];
while ($row = $result->fetch_assoc()) {
    $permissions[] = $row['name'];
}

$_SESSION["permissions"] = $permissions;
    

    header("Location: index.php");
} else {
    $_SESSION["signup_error"] = "Please try again.";
    header("Location: signup.php");
}

$stmt->close();
$conn->close();
?>