<?php 
  session_start(); 
  require_once("connect.php");
?>
<head>

    <title>Charles Casale - Cases</title>

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

  <?php include "nav.php"; ?>


  <div class = "main">
      <?php

      if (empty($_SESSION["user_id"])) {
        header("Location: login.php");
	      $_SESSION["login_error"] = "Please log in.";
        exit;
      }

      function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

	?>

    <div class="nav">
      <div>
        <h1>Case Dashboard</h1>
        <div class="small">Welcome back, <?php echo $_SESSION["name"]; ?></div>
      </div>
    </div>

    <button type="button" class="btn btn-dark w-100 mt-3 mb-3" data-bs-toggle="modal" data-bs-target="#createCaseForm"> 
      Create Case
    </button>

  	    <div class="modal fade" id="createCaseForm" data-show="" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Create Case</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="create_case_action.php" method="POST">
          
              <?php
              if (!empty($_SESSION["create_meeting_error"])) {
                  echo '<div class="alert alert-danger text-center" role="alert">';
                  echo htmlspecialchars($_SESSION["create_case_error"]);
                  echo "</div>";
                  $show_modal = true;
                  unset($_SESSION["create_case_error"]); 
              } else {
                  $show_modal = false;
              }
              ?>

              <input type="hidden" name="meeting_id" >

              <label>Case Title:</label><br>
              <input class="form-control" type="text" name="title" maxlength="50" required><br><br>

              <label>Court:</label><br>
              <input class="form-control" type="text" name="court" maxlength="50" required><br><br>

              <label>Case Type:</label><br>
              <input class="form-control" type="text" name="type" maxlength="20" required><br><br>

              <label>Filing Date:</label><br>
              <input class="form-control" type="date" name="filing_date" required><br><br>

              <label>Status:</label><br>
              <select class="form-control mb-3" name="status" required>
                  <option value="">--Select Status--</option>
                  <option value="Open">Open</option>
                  <option value="Closed">Closed</option>
                  <option value="Pending">Pending</option>
                  <option value="Appeal">Appeal</option>
              </select><br><br>


              <button type="submit" class = "btn btn-primary form-control">Create Case</button>

            </form>

      </div>
    </div>
  </div>
</div>

</div>
</body>
