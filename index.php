<!DOCTYPE html>
<?php session_start();?>
<html>

    <head>

        <title>Charles Casale - Home</title>

        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
        
        <script src="site.js" defer></script>
        <link rel="stylesheet" href = "style.css">
        <link rel="icon" type="image/x-icon" href="files/icon.png">

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" 
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" 
        crossorigin="anonymous"></script>

    </head>

    <body>

        <?php include "nav.php"; ?>



                <section id="home" class="hero">
  <h1>Powerful Legal Representation</h1>
  <p>Relentless advocacy. Strategic thinking. Results that matter.</p>
</section>

<section id="practice" class="section">
  <h2>Practice Areas</h2>
  <div class="practice-areas">

    <div class="card">
      <img src="https://img.icons8.com/ios-filled/100/law.png" />
      <h3>Workers' Compensation</h3>
      <p>Recover benefits and wages quickly.</p>
    </div>

    <div class="card">
      <img src="https://img.icons8.com/ios-filled/100/family.png" />
      <h3>Matrimonial Law</h3>
      <p>Divorce, custody, and family matters handled with care.</p>
    </div>

    <div class="card">
      <img src="https://img.icons8.com/ios-filled/100/car-crash.png" />
      <h3>Personal Injury</h3>
      <p>Maximum compensation for your injuries.</p>
    </div>

    <div class="card">
      <img src="https://img.icons8.com/ios-filled/100/handcuffs.png" />
      <h3>Criminal Defense</h3>
      <p>Protecting your rights and freedom.</p>
    </div>

  </div>
</section>

<section id="about" class="section">
  <h2>About the Attorney</h2>
  <p class="about">
    We deliver strategic, results-driven legal representation tailored to each client.
  </p>

  <div class="cta">
    <h2>Get Started Today</h2>
    <p>Click below to sign up and begin your case.</p>
    <button onclick="window.location.href='signup.php'">Sign Up</button>
  </div>
</section>

                <?php 
                
                if (isset($_SESSION["permission_error"])) {

                    echo '<div class="alert alert-danger text-center" role="alert">';
                    echo $_SESSION["permission_error"];
                    echo "</div>";
                    unset($_SESSION["permission_error"]);
                }
                
                ?>

                <br><br>
            </div>

   <?php
include "footer.php"; ?>

    </body>

</html>