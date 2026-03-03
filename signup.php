<!DOCTYPE html>

<html>

    <head>

        <title>Charles Casale - Signup</title>

        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
        
        <script src="site.js" defer></script>
        <link rel="stylesheet" href = "style.css">
        <link rel="icon" type="image/x-icon" href="">

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" 
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" 
        crossorigin="anonymous"></script>

    </head>

<?php include("nav.php"); ?>

<div class="container" style="padding: 20px;">
  <h2>Create Account</h2>

<div style="padding-bottom:120px;">

  <form action="process_signup.php" method="POST">
    <label>Username *</label><br>
    <input type="text" name="username" required><br><br>

    <label>Password *</label><br>
    <input type="password" name="password" required><br><br>

    <label>Confirm Password *</label><br>
    <input type="password" name="password_confirm" required><br><br>

    <label>First Name *</label><br>
    <input type="text" name="firstname" required><br><br>

    <label>Last Name *</label><br>
    <input type="text" name="lastname" required><br><br>

    <label>Email *</label><br>
    <input type="email" name="email" required><br><br>

    <label>Phone (optional)</label><br>
    <input type="text" name="phone"><br><br>

    <label>Address (optional)</label><br>
    <input type="text" name="address"><br><br>

    <button type="submit" style="display:block; margin-top:20px; padding:12px 18px; font-size:16px;">
  Sign Up
</button>
  </form>
</div>  
</div>

<?php include("footer.php"); ?>