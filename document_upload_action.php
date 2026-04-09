<?php
	session_start();
	require_once("connect.php");
    $name = $_POST["name"];
    $description = $_POST["description"];
    $case_id = $_POST["case_id"];
    $fileName = $_FILES["file"]["name"];
    $fileTmp = $_FILES["file"]["tmp_name"];
    $fileSize = $_FILES["file"]["size"];
    $fileError = $_FILES["file"]["error"];
    $filePath = "files/" . basename($fileName);

    if ($fileError === 0) {
	move_uploaded_file($fileTmp, $filePath);
	
	if ($case_id === "") {

        $sql = "INSERT INTO DOCUMENTS (name, description, path) VALUES (?, ?, ?)";
	$stmt = $conn->prepare($sql);
	$stmt->bind_param("sss", $name, $description, basename($fileName));
	$stmt->execute();

    }

    else {
    $sql = "INSERT INTO DOCUMENTS (name, case_id, description, path) VALUES (?, ?, ?, ?)";
	$stmt = $conn->prepare($sql);
	$stmt->bind_param("siss", $name, $case_id, $description, basename($fileName));
	$stmt->execute();


    }

	}
	else {
        $_SESSION["document_error"] = "Failed to upload file";
    }


	header("Location: documents.php");
        exit;
?>