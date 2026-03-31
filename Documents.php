<?php 
  session_start(); 
  require_once("connect.php");
?>
<head>

    <title>Charles Casale - Documents</title>

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

    <div class="nav">
      <div>
        <h1>Document Dashboard</h1>
        <div class="small">Welcome back, <?php echo $_SESSION["name"]; ?></div>
      </div>
    </div>

      <?php

      if (empty($_SESSION["user_id"])) {
        header("Location: login.php");
	      $_SESSION["login_error"] = "Please log in.";
        exit;
      }

      function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }





	if (isset($_SESSION["document_error"])) {
		echo $_SESSION["document_error"];
		unset($_SESSION["document_error"]);

 } ?>


	<button class="btn btn-dark flex-fill" data-bs-toggle="modal" data-bs-target="#uploadDocument">Upload Document</button>
	
	<div class="modal fade" id="uploadDocument" tabindex="-1" aria-labelledby="u" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    
                    <div class="modal-header">
                      <h5 class="modal-title">Upload Document</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                      <form action="document_upload_action.php" method="POST" enctype= "multipart/form-data">
          <input type="hidden" name="meeting_id" value="<?= $row['meeting_id']; ?>">

          <?php
          if (!empty($_SESSION["document_upload_error"])) {
              echo '<div class="alert alert-danger text-center" role="alert">';
              echo htmlspecialchars($_SESSION["document_upload_error"]);
              echo "</div>";
          }
          ?>

          <label class="form-label">Name:</label>
          <input type="text" class="form-control mb-3" name="name" required>

          <label class="form-label">Select file:</label>
          <input type="file" class="form-control mb-3" name="file" required>

	  <label class="form-label">Describe the file:</label>
          <input type="text" class="form-control mb-3" name="description">

	  <button type="submit" class="form-control btn btn-success">Submit</button>
        </form>
                    </div>
                    
                    <div class="modal-footer">
                
                    </div>
                  </div>
                </div>
              </div>




</div>
</body>
