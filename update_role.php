<?php 

    session_start();
    include "connect.php";

    $user_id = $_POST["user_id"];
    $role = $_POST["role"];

    $stmt = $conn->prepare("UPDATE USERS SET role = ? WHERE user_id = ?");
    $stmt->bind_param("si", $role, $user_id);

    if ($stmt->execute()) {
        $_SESSION["role_update_success"] = "Role updated successfully.";
    } else {
        $_SESSION["update_user_error"] = "Error updating role: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

    header("Location: users.php");
    exit;



?>