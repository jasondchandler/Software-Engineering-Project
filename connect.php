<?php

// If you downloaded AMPPS to your own computer,
// do cd ("C:\Program Files\Ampps\mysql\bin") in powershell
// change parentheses to wherever you have that bin folder
// Then press enter and type .\mysql -u root -p
// The password should be mysql

    
    #$conn = mysqli_connect("localhost", "root", "root");
    $conn = mysqli_connect("localhost", "root", "mysql");
    if(!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    mysqli_select_db($conn, "CasaleTest") or die("Could not connect to the database");

?>