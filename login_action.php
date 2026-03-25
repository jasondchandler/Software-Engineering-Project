<?php 

require_once("connect.php");
session_start();

$email = trim($_POST["email"]);
$password = $_POST["password"];

$stmt = $conn->prepare("SELECT user_id, firstname, lastname, email, password, role FROM users WHERE email=?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row && password_verify($password, $row["password"])) {
    $_SESSION["name"] = $row["firstname"] . " " . $row["lastname"];
    $_SESSION["user_id"] = $row["user_id"];
    if ($_SESSION["name"] === "charles casale") {
        $_SESSION["role"] = "admin";
    } else {
        $_SESSION["role"] = "client";
    }

	$sql = "SELECT name FROM PERMISSIONS WHERE role = ?";

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
    exit;
} else {
        $_SESSION["login_error"] = "Invalid login. Please try again.";
        header("Location: login.php");
        exit;
   }

?>