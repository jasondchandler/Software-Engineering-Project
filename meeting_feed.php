<?php 
            
              if (!empty($_SESSION["edit_meeting_error"])) {
              echo '<div class="alert alert-danger text-center" role="alert">';
              echo htmlspecialchars($_SESSION["edit_meeting_error"]);
              echo "</div>";
          }
		
	include 'role_function.php';            

            ?>
<div class="search_container">
<form method="GET" action="">
        <label>
            <input type="checkbox" name="hide_pending" value="1" <?php if(!empty($_GET['hide_pending'])) echo 'checked'; ?>> Hide Pending
        </label>
        <label>
            <input type="checkbox" name="hide_noshow" value="1" <?php if(!empty($_GET['hide_noshow'])) echo 'checked'; ?>> Hide No-Show
        </label>
        <label>
            <input type="checkbox" name="hide_complete" value="1" <?php if(!empty($_GET['hide_complete'])) echo 'checked'; ?>> Hide Completed
        </label>
        <button type="submit" class="btn btn-primary w-100 mt-2">Apply Filter</button>
    </form>
        </div>
<?php  

    $filters = [];
    if (!empty($_GET['hide_pending'])) {
        $filters[] = "m.status != 'pending'";
    }
    if (!empty($_GET['hide_noshow'])) {
        $filters[] = "m.status != 'no_show'";
    }
    if (!empty($_GET['hide_complete'])) {
        $filters[] = "m.status != 'complete'";
    }

    $where = "m.status != 'cancelled'";
    if ($filters) {
        $where .= " AND " . implode(" AND ", $filters);
    }

if ($_SESSION["role"] === "client") {
  $sql = "
  SELECT m.meeting_id, m.notes, m.status, m.location,
         m.duration,mt.start_time, mt.end_time
  FROM meetings m
  LEFT JOIN meeting_times mt ON mt.meeting_id = m.meeting_id
  WHERE $where 
    AND m.user_id = ?
  ORDER BY mt.start_time ASC";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $_SESSION["user_id"]);
  $stmt->execute();
  $result = $stmt->get_result();
} 
elseif ($_SESSION["role"] === "admin") {

    $sql = "
    SELECT m.meeting_id, m.notes, m.status, m.location,
           m.duration, mt.start_time, mt.end_time, u.firstname, u.lastname, u.email
    FROM meetings m
    LEFT JOIN meeting_times mt ON mt.meeting_id = m.meeting_id
    LEFT JOIN users u ON m.user_id = u.user_id
    WHERE $where
    ORDER BY mt.start_time ASC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
}
?>

  <?php $count=1; if ($result && $result->num_rows > 0): ?>
    <?php while ($row = $result->fetch_assoc()): ?>
        <div class="meeting">
            <span>Meeting #<?php echo $count; ?></span><br>
            <span>Status: <?php echo $row["status"];?></span> <br>
            <span>Time: 
              <?php  
                $timezone = new DateTimeZone("America/New_York");
                $start = new DateTime($row["start_time"], $timezone);
                $end = new DateTime($row["end_time"], $timezone);
                $formatted_time = $start->format("M j, Y g:i A") . " → " . $end->format("g:i A");
                echo $formatted_time;
              ?>
            </span><br>
            <span>Location: <?php echo $row["location"];?></span><br>
            
            <?php 
            if ($_SESSION["role"] === "admin") {
              echo '<span>Participants: ';
              echo $row["firstname"] . ' ' . $row["lastname"] . ' | ' . $row["email"] . '<br>';
            }
            ?></span>

            <?php 
              if ($row["notes"] !== null && $row["notes"] !== "") {
                  echo "<span>Notes: " . htmlspecialchars($row["notes"]) . "</span><br>";
              } else {
                  echo "<span>Notes: None</span><br>";
              }
            ?>

            <?php 
            
              if (!empty($_SESSION["edit_meeting_error"])) {
              echo '<div class="alert alert-danger text-center" role="alert">';
              echo htmlspecialchars($_SESSION["edit_meeting_error"]);
              echo "</div>";
          }
            
            ?>

            <hr>
            <div class="d-flex flex-wrap gap-2">
              <?php 


              if (allow("confirm-meetings") && $row["status"] === "pending") {
                echo '<button class="btn btn-success flex-fill" data-bs-toggle="modal" data-bs-target="#confirmMeeting' . $row['meeting_id'] . '">Confirm</button>';
                echo "<br>";
              }
              elseif ($_SESSION["role"] === "admin" && $row["status"] === "confirmed") {
                echo '<button class="btn btn-success flex-fill" data-bs-toggle="modal" data-bs-target="#completeMeeting' . $row['meeting_id'] . '">Complete</button>';
              }
              
              
              if (($row["status"] === "pending" || $row["status"] === "confirmed")) {
                  echo '<button class="btn btn-warning flex-fill" data-bs-toggle="modal" data-bs-target="#editMeetingForm' . $row['meeting_id'] . '">Edit</button>
                        <button class="btn btn-danger flex-fill" data-bs-toggle="modal" data-bs-target="#deleteMeeting' . $row['meeting_id'] . '">Delete</button>';
              }

              if ($_SESSION["role"] === "admin" && $row["status"] === "confirmed") {
                echo '<br><button class="btn btn-dark d-block w-100" data-bs-toggle="modal" data-bs-target="#noShowMeeting' . $row['meeting_id'] . '">No-show</button>';
              } 

              ?>
              
            </div>
          
            <?php if ($row["status"] === "pending"): ?>
              <div class="modal fade" id="confirmMeeting<?= $row['meeting_id'] ?>" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    
                    <div class="modal-header">
                      <h5 class="modal-title">Confirm Meeting</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                      Are you sure you want to confirm this meeting?
                    </div>
                    
                    <div class="modal-footer">
                      <form action="confirm_meeting.php" method="POST">
                        <input type="hidden" name="meeting_id" value="<?= $row['meeting_id'] ?>">
                        
                        <button type="submit" class="btn btn-success">Yes</button>
                      </form>
                      <button type="button" class="btn btn-danger" data-bs-dismiss="modal">No</button>
                    </div>
                  </div>
                </div>
              </div>

            <?php elseif ($row["status"] === "confirmed"): ?>
              <div class="modal fade" id="noShowMeeting<?= $row['meeting_id'] ?>" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    
                    <div class="modal-header">
                      <h5 class="modal-title">Mark no-show</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                      Are you sure you want to mark as no-show?
                    </div>
                    
                    <div class="modal-footer">
                      <form action="no_show_meeting.php" method="POST">
                    <input type="hidden" name="meeting_id" value="<?= $row['meeting_id'] ?>">
                    
                    <button type="submit" class="btn btn-success">Yes</button>
                  </form>
                      <button type="button" class="btn btn-danger" data-bs-dismiss="modal">No</button>
                    </div>

                  </div>
                </div>
              </div>
            <?php endif;?>


             <?php 
$show_edit_modal = false;
if (!empty($_SESSION["edit_meeting_error"])) {
    $show_edit_modal = true;
}
?>

<div class="modal fade" id="editMeetingForm<?= $row['meeting_id'] ?>" 
     data-edit-modal="<?php echo $show_edit_modal ? 'true' : 'false'; ?>" 
     tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Meeting</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="edit_meeting.php" method="POST">
          <input type="hidden" name="meeting_id" value="<?= $row['meeting_id']; ?>">

          <?php
          if (!empty($_SESSION["edit_meeting_error"])) {
              echo '<div class="alert alert-danger text-center" role="alert">';
              echo htmlspecialchars($_SESSION["edit_meeting_error"]);
              echo "</div>";
          }
          ?>

          <label class="form-label">Date:</label>
          <input type="date" class="form-control mb-3" name="date" 
                 value="<?= (new DateTime($row["start_time"]))->format("Y-m-d") ?>" required>

          <label class="form-label">Time:</label>
          <input type="time" class="form-control mb-3" name="time" 
                 value="<?= (new DateTime($row['start_time']))->format("H:i:s") ?>" required>

          <label class="form-label">Location:</label>
          <input type="text" class="form-control mb-3" name="location" 
                 value="<?= htmlspecialchars($row['location']); ?>" required>

          <label class="form-label">Estimated Duration (mins):</label>
          <input type="number" class="form-control mb-3" name="duration" 
                 value="<?= $row['duration']; ?>" min="15" max="180" required>

          <label class="form-label">Notes:</label>
          <textarea name="notes" class="form-control mb-3"><?= htmlspecialchars($row["notes"]); ?></textarea>

          <button type="submit" class="btn btn-dark form-control">Update Meeting</button>
        </form>
      </div>
    </div>
  </div>
</div>
              <div class="modal fade" id="deleteMeeting<?= $row['meeting_id']?>" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              
              <div class="modal-header">
                <h5 class="modal-title">Delete Meeting</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>

              <div class="modal-body">
                Are you sure you want to delete this meeting?
              </div>
              
              <div class="modal-footer">
                <form action="delete_meeting.php" method="POST">
                  <input type="hidden" name="meeting_id" value="<?= $row['meeting_id'] ?>">
                  
                  <button type="submit" class="btn btn-success">Yes</button>
                </form>
              
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">No</button>
              </div>

            </div>
          </div>
        </div>

          <div class="modal fade" id="completeMeeting<?= $row['meeting_id']?>" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              
              <div class="modal-header">
                <h5 class="modal-title">Complete Meeting</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>

              <div class="modal-body">
                Are you sure you want to mark as complete?
              </div>
              
              <div class="modal-footer">
                <form action="complete_meeting.php" method="POST">
                  <input type="hidden" name="meeting_id" value="<?= $row['meeting_id'] ?>">
                  
                  <button type="submit" class="btn btn-success">Yes</button>
                </form>
              
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">No</button>
              </div>

            </div>
          </div>
        </div>

        </div>
      <?php $count++;?>
    <?php endwhile; ?>
  <?php else: ?>
    <p>No meetings found.</p>
  <?php endif; ?>
  </div>