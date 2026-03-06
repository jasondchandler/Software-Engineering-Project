<!DOCTYPE html>
<html>
<head>
<title>Case Entry Form</title>

<style>
body{
    font-family: Arial;
    background-color:#f4f4f4;
}

.container{
    width:400px;
    margin:50px auto;
    padding:20px;
    background:white;
    border-radius:8px;
    box-shadow:0 0 10px rgba(0,0,0,0.2);
}

input{
    width:100%;
    padding:8px;
    margin-top:5px;
    margin-bottom:15px;
}

button{
    width:100%;
    padding:10px;
    background:#2e86de;
    color:white;
    border:none;
    font-size:16px;
}
</style>

</head>

<body>

<div class="container">

<h2>Enter Case Information</h2>

<form method="post">

<label>Start Date *</label>
<input type="date" name="startDate" required>

<label>Status *</label>
<input type="text" name="status" required>

<label>Type of Problem *</label>
<input type="text" name="type" required>

<button type="submit" name="submit">Submit Case</button>

</form>

<?php

if(isset($_POST['submit'])){

    $startDate = $_POST['startDate'];
    $status = $_POST['status'];
    $type = $_POST['type'];

    if(empty($startDate) || empty($status) || empty($type)){
        echo "<p style='color:red;'>Please fill in all fields.</p>
    

  

</div>

</body>
</html>
```
