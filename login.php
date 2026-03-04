<?php
session_start();

?>
<!doctype html>
<html>
 <head>

        <title>Charles Casale - Home</title>

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
  <h2>Login</h2>

  <?php 
    if (isset($_SESSION["login_error"]) && !empty($_SESSION["login_error"])) {
      echo '<div class="alert alert-danger" role="alert">';
      echo htmlspecialchars($_SESSION["login_error"]);
      echo "</div>";
      unset($_SESSION["login_error"]);
    }
  ?>

  <form action="login_action.php" method="POST">
    <label class="form-label">Email:</label>
        <input type="email" class="form-control" name="email" required>  

    <label class="form-label">Password</label>
    <input type="password" name="password" class="form-control" required>

    <button class="btn btn-primary form-control">Login</button>
  </form>
</div>

</body>
</html>