<?php  
	require_once("connect.php");

	$sql = "SELECT * FROM MEETINGS WHERE user_id =" . $_SESSION["user_id"];
	
	$stmt = $conn->prepare($sql);
	$stmt->execute();
	$result = $stmt->get_result();
	
	$count = 1;
	while ($row = $result->fetch_assoc()) {

		$date = $row["meeting_date"];
		$time = $row["meeting_time"];
		$location = $row["location"];
		$status = $row["status"];

		echo '<div class="stats w-100">

    <div class="stat">
      <div class="small">Meeting ' . $count . '</div>';
       echo '<div class="num">Date: ' . $date . '</div>';
       echo '<div class="num">Time: ' . $time . '</div>';
       echo '<div class="num">Location: ' . $location . '</div>';
	echo '<div class="num">Status: '. $status . '</div>';

	echo '<button type="submit" class = "btn btn-warning form-control w-100 mb-3 mt-3">Edit Meeting</button>';

		echo '<button type="submit" class = "btn btn-danger form-control w-100">Delete Meeting</button>'; 
    echo '</div>';

	echo '</div>';


		$count+=1;

	}

	$result->free_result();

?>


