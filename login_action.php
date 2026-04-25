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
    $_SESSION["role"] = $row["role"];

	$sql = "
    SELECT p.name
    FROM permissions p
    JOIN role_permissions rp 
        ON p.permission_id = rp.permission_id
    WHERE rp.role_name = ?
    ";

	$stmtPerm = $conn->prepare($sql);
    $stmtPerm->bind_param("s", $_SESSION["role"]);
    $stmtPerm->execute();
    $resultPerm = $stmtPerm->get_result();

    $permissions = [];
    while ($permRow = $resultPerm->fetch_assoc()) {
        $permissions[$permRow['name']] = true;
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
