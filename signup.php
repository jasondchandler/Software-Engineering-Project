<?php session_start(); ?>

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

  <body>

  <div class="form_container">
      <h2>Create Account</h2>

      <?php 
      if (isset($_SESSION["signup_error"]) && !empty($_SESSION["signup_error"])) {
        echo '<div class="alert alert-danger" role="alert">';
        echo htmlspecialchars($_SESSION["signup_error"]);
        echo "</div>";
        unset($_SESSION["signup_error"]);
      }
      ?>

      <form action="process_signup.php" method="POST">
       
        <label class="form-label">Email:</label>
        <input type="email" class="form-control" name="email" required>

        <label class="form-label">Password:</label>
        <input type="password" class="form-control" name="password" required>

        <label class="form-label">Confirm Password:</label>
        <input type="password" class="form-control" name="password_confirm" required>

        <label class="form-label">First Name:</label>
        <input type="text" class="form-control" name="firstname" required>

        <label class="form-label">Last Name:</label>
        <input type="text" class="form-control" name="lastname" required>

        <label class="form-label">Phone:</label>
        <input type="text" class="form-control" name="phone">

        <label class="form-label">Address:</label>
        <input type="text" class="form-control" name="address">

        <button type="submit" class="btn btn-primary form-control">
      Sign Up
    </button>
  </form>
</div>  

  </body>

</html>
