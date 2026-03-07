<?php  


if ($_SESSION["role"] === "client") {
  $sql = "
  SELECT m.meeting_id, m.notes, m.status, m.location,
         m.duration,mt.start_time, mt.end_time
  FROM meetings m
  LEFT JOIN meeting_times mt ON mt.meeting_id = m.meeting_id
  WHERE m.status != 'cancelled' AND m.user_id = ? 
  ORDER BY mt.start_time ASC";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $_SESSION["user_id"]);
  $stmt->execute();
  $result = $stmt->get_result();
} elseif ($_SESSION["role"] === "admin") {
  $sql = "
    SELECT m.meeting_id, m.notes, m.status, m.location,
          m.duration,mt.start_time, mt.end_time, u.firstname, u.lastname, u.email
    FROM meetings m
    LEFT JOIN meeting_times mt ON mt.meeting_id = m.meeting_id
    LEFT JOIN users u ON m.user_id = u.user_id
    WHERE m.status != 'cancelled'
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
            <hr>
            <div class="d-flex w-100 gap-2">
              <?php if ($_SESSION["role"] === "admin") {
                echo '<button class="btn btn-success flex-fill" data-bs-toggle="modal" data-bs-target="#confirmMeeting' . $row['meeting_id'] . '">Confirm</button>';
              }?>
              <button class="btn btn-warning flex-fill" data-bs-toggle="modal" data-bs-target="#editMeetingForm<?= $row['meeting_id'] ?>">Edit</button>
              <button class="btn btn-danger flex-fill" data-bs-toggle="modal" data-bs-target="#deleteMeeting<?= $row['meeting_id'] ?>">Delete</button>
            </div>

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
                <a href="confirm_meeting.php?id=<?= $row['meeting_id'] ?>" class="btn btn-success">Yes</a>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">No</button>
              </div>

            </div>
          </div>
        </div>

              <div class="modal fade" id="editMeetingForm<?= $row['meeting_id'] ?>" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">Edit Meeting</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                      <form action="edit_meeting.php" method="POST">
                        <input type="hidden" name="meeting_id" value="<?php echo $row['meeting_id']; ?>">
                            <label class="form-label">Date:</label>
                            <input type="date" class="form-control mb-3" name="date" value="<?php echo (new DateTime($row["start_time"]))->format("Y-m-d"); ?>" required>
                        
                            <label class="form-label">Time:</label>
                            <input type="time" class="form-control mb-3" name="time" min="09:00" max="17:00" value="<?php echo (new DateTime($row['start_time']))->format("H:i:s"); ?>" required>
                                               
                            <label class="form-label">Location:</label>
                            <input type="text" class="form-control mb-3" name="location" value="<?php echo $row['location'];?>" required>
                                              
                            <label class="form-label">Estimated Duration (mins):</label>
                            <input type="number" class="form-control mb-3" name="duration" min="15" max="180" value="<?php echo $row['duration'];?>" required>
                                              
                            <label class="form-label">Notes:</label>
                            <textarea name="notes" class="form-control mb-3"><?php echo htmlspecialchars($row["notes"]); ?></textarea>
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
                <a href="delete_meeting.php?id=<?= $row['meeting_id'] ?>" class="btn btn-success">Yes</a>
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