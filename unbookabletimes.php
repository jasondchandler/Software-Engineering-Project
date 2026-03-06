<!DOCTYPE html>
<html>
<head>
<title>Unbookable Time Form</title>

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

<h2>Enter Unbookable Time</h2>

<form method="post">

<label>Date *</label>
<input type="date" name="date" required>

<label>Start Time *</label>
<input type="time" name="start_time" required>

<label>End Time *</label>
<input type="time" name="end_time" required>

<label>Reason</label>
<input type="text" name="reason" placeholder="Meeting, vacation, etc.">

<button type="submit" name="submit">Submit</button>

</form>

<?php

if(isset($_POST['submit'])){

    $date = $_POST['date'];
    $start = $_POST['start_time'];
    $end = $_POST['end_time'];
    $reason = $_POST['reason'];

    if(empty($date) || empty($start) || empty($end)){
        echo "<p style='color:red;'>Please fill in all required fields.</p>

</div>

</body>
</html>
```
