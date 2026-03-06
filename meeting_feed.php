<?php  


if ($_SESSION["role"] === "client") {
  $sql = "
  SELECT m.meeting_id, m.notes, m.status, m.location,
         m.duration,mt.start_time, mt.end_time
  FROM meetings m
  LEFT JOIN meeting_times mt ON mt.meeting_id = m.meeting_id
  WHERE m.user_id = ? 
  ORDER BY mt.start_time ASC";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $_SESSION["user_id"]);
  $stmt->execute();
  $result = $stmt->get_result();
} else {
  $sql = "
    SELECT m.meeting_id, m.notes, m.status, m.location,
          m.duration,mt.start_time, mt.end_time
    FROM meetings m
    LEFT JOIN meeting_times mt ON mt.meeting_id = m.meeting_id
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
            <span>Location: <?php echo $row["location"];?></span><br><hr>
            <?php 
              if(!isset($row["notes"])) {
                echo "<span>Notes: " . htmlspecialchars($row["notes"]) . "</span><br>";
              }
            ?>
            <div class="d-flex w-100 gap-2">
              <?php if ($_SESSION["role"] === "admin") {
                echo '<button class="btn btn-success flex-fill" data-bs-toggle="modal" data-bs-target="#confirmMeeting">Confirm</button>';
              }?>
              <button class="btn btn-warning flex-fill" data-bs-toggle="modal" data-bs-target="#editMeetingForm">Edit</button>
              <button class="btn btn-danger flex-fill" data-bs-toggle="modal" data-bs-target="#deleteMeeting<?= $row['meeting_id'] ?>">Delete</button>
            </div>

          <div class="modal fade" id="confirmMeeting" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
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

              <div class="modal fade" id="editMeetingForm" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">Edit Meeting</h5>
                      <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                      
                      <?php include "edit_meeting_form.php"?>

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

