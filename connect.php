<?php

// If you downloaded AMPPS to your own computer,
// do cd "C:\Program Files\Ampps\mysql\bin" in powershell
// change parentheses to wherever you have that bin folder
// Then press enter and type .\mysql -u root -p
// The password should be mysql

    
    #$conn = mysqli_connect("localhost", "root", "root");
    $conn = mysqli_connect("localhost", "root", "mysql");
    #$conn = mysqli_connect("sql101.infinityfree.com", "if0_41737126", "acAlFuJqyY");
    if(!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    mysqli_select_db($conn, "test5") or die("Could not connect to the database");
    #mysqli_select_db($conn, "if0_41737126_test4") or die("Could not connect to the database");
?>