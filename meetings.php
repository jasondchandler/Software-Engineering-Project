<?php 
  session_start(); 
  require_once("connect.php");
?>
<head>

    <title>Charles Casale - Meetings</title>

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

      $pendingCount = 0;
      $upcomingCount = 0;

      $r1 = $conn->query("SELECT COUNT(*) AS c FROM meetings WHERE status='pending'");
      if ($r1) { $pendingCount = (int)$r1->fetch_assoc()["c"]; }

      $r2 = $conn->query("
        SELECT COUNT(*) AS c
        FROM meetings m
        JOIN meeting_times mt ON mt.meeting_id = m.meeting_id
        WHERE m.status IN ('confirmed')
      ");
      if ($r2) { $upcomingCount = (int)$r2->fetch_assoc()["c"]; }
      ?>

  <div class="container">

    <div class="nav">
      <div>
        <strong>Dashboard</strong>
        <div class="small">Welcome back, <?php echo $_SESSION["name"]; ?></div>
      </div>
    </div>

    <div class="stats">

      <div class="stat">
        <div class="small">Pending Requests</div>
        <div class="num"><?php echo $pendingCount; ?></div>
        <div class="small">Awaiting review</div>
      </div>

      <div class="stat">
        <div class="small">Upcoming Meetings</div>
        <div class="num"><?php echo $upcomingCount; ?></div>
        <div class="small">Scheduled</div>
      </div>
    </div>

    <button type="button" class="btn btn-dark w-100 mt-3 mb-3" data-bs-toggle="modal" data-bs-target="#createMeetingForm"> 
      Create Meeting
    </button>

    <?php 
      $show_modal = false;
      if (!empty($_SESSION["create_meeting_error"])) {
          $show_modal = true;
      }
    ?>

    <div class="modal fade" id="createMeetingForm" data-show="<?php echo !empty($show_modal) ? 'true' : 'false'; ?>" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Create Meeting</h5>
            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
          </div>
          <div class="modal-body">
            <form action="create_meeting.php" method="POST">
          
              <?php
              if (!empty($_SESSION["create_meeting_error"])) {
                  echo '<div class="alert alert-danger text-center" role="alert">';
                  echo htmlspecialchars("There is a time conflict.");
                  echo "</div>";
                  $show_modal = true;
                  unset($_SESSION["create_meeting_error"]); 
              } else {
                  $show_modal = false;
              }
              ?>

              <input type="hidden" name="meeting_id" value="<?php ?>">

              <div class="mb-3">
                  <label class="form-label">Date:</label>
                  <input type="date" class="form-control" name="date" value="<?php  ?>" required>
              </div>

              <div class="mb-3">
                  <label class="form-label">Time:</label>
                  <input type="time" class="form-control" name="time" value="<?php  ?>" required>
              </div>

              <div class="mb-3">
                  <label class="form-label">Location:</label>
                  <input type="text" class="form-control" name="location" value="<?php ?>" required>
              </div>

              <div class="mb-3">
                  <label class="form-label">Duration (mins):</label>
                <input type="number" class="form-control" name="duration" min="1" max="180" value="<?php ?>" required>
              </div>

              <div class="mb-3">
                <label class="form-label">Notes:</label>
                <textarea name="notes" class="form-control"></textarea>
              </div>

              <?php 

                if ($_SESSION["role"] === "admin") {
                  $result = $conn->query("SELECT user_id, firstname, lastname, email FROM users");

                  echo '<div class="mb-3">
                          <label class="form-label">Select a user to meet with:</label>
                          <select name="user_id" class="form-control">';

                  while ($row = $result->fetch_assoc()) {
                      echo '<option value="' . $row["user_id"] . '">'
                          . $row["firstname"] . ' ' . $row["lastname"] . ' | ' . $row["email"] .
                          '</option>';
                  }

                  echo '</select></div>';
                  }
              ?>

              <button type="submit" class = "btn btn-primary form-control">Create Meeting</button>

            </form>

      </div>
    </div>
  </div>
</div>




<br><br>
        <h1>Your meetings: </h1>

	<?php include "meeting_feed.php"?>


</body>
