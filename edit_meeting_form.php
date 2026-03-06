<form action="edit_meeting.php" method="POST">
                    
                        <input type="hidden" name="meeting_id" value="<?php echo $row['meeting_id']; ?>">

                        <div class="mb-3">
                            <label class="form-label">Date:</label>
                            <input type="date" class="form-control" name="date" value="<?php echo (new DateTime($row["start_time"]))->format("Y-m-d"); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Time:</label>
                            <input type="time" class="form-control" name="time" value="<?php echo (new DateTime($row['start_time']))->format("H:i:s"); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Location:</label>
                            <input type="text" class="form-control" name="location" value="<?php echo $row['location'];?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Estimated Duration (mins):</label>
                            <input type="number" class="form-control" name="duration" min="15" max="180" value="<?php echo $row['duration'];?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Notes:</label>
                            <textarea name="notes" class="form-control" value = <?php echo $row["notes"]?>> </textarea>
                        </div>

                        <button type="submit" class="btn btn-dark form-control">Update Meeting</button>

                    </form>