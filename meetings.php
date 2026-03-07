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
        <h1>Meeting Dashboard</h1>
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
    
      if ($_SESSION["role"] === "admin") {
        echo '<button type="button" class="btn btn-dark w-100 mt-3 mb-3" data-bs-toggle="modal" data-bs-target="#unavailableTimeForm"> 
          Set Unavailiable Times
        </button><br>';

         echo '<button type="button" class="btn btn-dark w-100 mt-3 mb-3" data-bs-toggle="modal" data-bs-target="#removeTimesForm"> 
          Remove Unavailiable Times
        </button><br>';
      }


    
    ?>

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
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="create_meeting.php" method="POST">
          
              <?php
              if (!empty($_SESSION["create_meeting_error"])) {
                  echo '<div class="alert alert-danger text-center" role="alert">';
                  echo htmlspecialchars($_SESSION["create_meeting_error"]);
                  echo "</div>";
                  $show_modal = true;
                  unset($_SESSION["create_meeting_error"]); 
              } else {
                  $show_modal = false;
              }
              ?>

              <input type="hidden" name="meeting_id" >

              <div class="mb-3">
                  <label class="form-label">Date:</label>
                  <input type="date" class="form-control" name="date" required>
              </div>

              <div class="mb-3">
                  <label class="form-label">Time:</label>
                  <input type="time" class="form-control" name="time" min="09:00" max="17:00" required>
              </div>

              <div class="mb-3">
                  <label class="form-label">Location:</label>
                  <input type="text" class="form-control" name="location"  required>
              </div>

              <div class="mb-3">
                  <label class="form-label">Duration (mins):</label>
                <input type="number" class="form-control" name="duration" min="1" max="180"  required>
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

<div class="modal fade" id="unavailableTimeForm" data-show="<?php echo !empty($show_modal) ? 'true' : 'false'; ?>" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Set Unavailable Times</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="set_unavailable_times.php" method="POST">

              <div class="mb-3">
                  <label class="form-label">Date:</label>
                  <input type="date" class="form-control" name="date" required>
              </div>

              <div class="mb-3">
                  <label class="form-label">Start Time:</label>
                  <input type="time" class="form-control" name="start_time" min="09:00" max="17:00" required>
              </div>

              <div class="mb-3">
                  <label class="form-label">End Time:</label>
                  <input type="time" class="form-control" name="end_time" min="09:00" max="17:00" required>
              </div>

              <div class="mb-3">
                  <label class="form-label">Repeat Daily?:</label>
                  <input type="checkbox" id="repeat" name="repeat">
              </div>

              <button type="submit" class = "btn btn-primary form-control">Ban Times</button>

            </form>

      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="removeTimesForm" data-show="<?php echo !empty($show_modal) ? 'true' : 'false'; ?>" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Set Unavailable Times</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>

          <?php 
          
            $sql = "select times_id, date, start_time, end_time, repeat_daily from unavailable_times";
            $result = $conn->query($sql);
            $unavailable_times = [];
            if ($result->num_rows > 0) {

                while ($row = $result->fetch_assoc()) {
                    $unavailable_times[] = $row;
                }

            }
          ?>

          <div class="modal-body">
            <?php if (!empty($unavailable_times)):?>
              <form action = "remove_times.php" method="POST">
                <label class="form-label">Select Time to Remove:</label>
                <select name="unavailable_id" class="form-select" required>

                  <?php foreach ($unavailable_times as $time): ?>

                    <option value="<?php echo $time['times_id']; ?>">
                      <?php echo $time["date"] . " | " . substr($time["start_time"],0,5) . " - " . substr($time['end_time'],0,5); ?>
                      <?php echo $time["repeat_daily"] ? " (Repeats Daily)" : ""; ?></option>

                  <?php endforeach; ?>
                </select>
                <button type="submit" class = "btn btn-primary form-control">Remove Selected Unavailable Time</button>

              </form>

            <?php endif; ?>

      </div>
    </div>
  </div>
</div>




<br><br>
        <h1>Your meetings: </h1>

	<?php include "meeting_feed.php"?>


</body>
