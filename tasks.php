<?php 
  session_start(); 
  require_once("connect.php");
?>
<head>

    <title>Charles Casale - Tasks</title>

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
	<div class="container">
    	<div class="nav">
        	<div>
            	<h1>Task Dashboard</h1>
            	<div class="small">Welcome back, <?php echo $_SESSION["name"]; ?></div>
        	</div>
    	</div>

    	<?php
    	if (!empty($_SESSION["task_error"])) {
        	echo '<div class="alert alert-danger text-center" role="alert">';
        	echo htmlspecialchars($_SESSION["task_error"]);
        	echo '</div>';
        	unset($_SESSION["task_error"]);
    	}

    	if (!empty($_SESSION["task_success"])) {
        	echo '<div class="alert alert-success text-center" role="alert">';
        	echo htmlspecialchars($_SESSION["task_success"]);
        	echo '</div>';
        	unset($_SESSION["task_success"]);
    	}
    	?>

    	<?php if ($_SESSION["role"] === "admin"): ?>
        	<button type="button" class="btn btn-dark w-100 mt-3 mb-3" data-bs-toggle="modal" data-bs-target="#assignTaskForm">
            	Assign Task
        	</button>

        	<div class="modal fade" id="assignTaskForm" tabindex="-1" aria-hidden="true">
            	<div class="modal-dialog">
                	<div class="modal-content">

                    	<div class="modal-header">
                        	<h5 class="modal-title">Assign Task</h5>
                        	<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    	</div>

                    	<div class="modal-body">
                        	<form action="create_task.php" method="POST">

                            	<div class="mb-3">
                                	<label class="form-label">Assign To:</label>
                                	<select name="user_id" class="form-control" required>
                                    	<?php
                                    	$result = $conn->query("SELECT user_id, firstname, lastname, email, role FROM users WHERE role IN ('client', 'paralegal')");
                                    	while ($row = $result->fetch_assoc()) {
                                        	echo '<option value="' . $row["user_id"] . '">'
                                            	. htmlspecialchars($row["firstname"] . ' ' . $row["lastname"] . ' | ' . $row["email"] . ' | ' . $row["role"])
                                            	. '</option>';
                                    	}
                                    	?>
                                	</select>
                            	</div>

                            	<div class="mb-3">
                                	<label class="form-label">Task Description:</label>
                                	<textarea name="description" class="form-control" required></textarea>
                            	</div>

                            	<div class="mb-3 form-check">
                                	<input type="checkbox" class="form-check-input" id="digital" name="can_complete_digitally" value="1">
                                	<label class="form-check-label" for="digital">
                                    	Can be completed digitally
                                	</label>
                            	</div>

                            	<button type="submit" class="btn btn-primary form-control">Assign Task</button>
                        	</form>
                    	</div>

                	</div>
            	</div>
        	</div>
    	<?php endif; ?>
		<hr>
		<h2>Your Tasks</h2>

		<?php
		if ($_SESSION["role"] === "admin") {
    		$sql = "SELECT t.task_id, t.description, t.can_complete_digitally, t.status, t.created_at,
                   u.firstname, u.lastname, u.email, u.role
            		FROM TASKS t
            		JOIN USERS u ON t.user_id = u.user_id
            		ORDER BY t.created_at DESC";
    		$stmt = $conn->prepare($sql);
    		$stmt->execute();
		} else {
    		$sql = "SELECT task_id, description, can_complete_digitally, status, created_at
            		FROM TASKS
            		WHERE user_id = ?
            		ORDER BY created_at DESC";
    		$stmt = $conn->prepare($sql);
    		$stmt->bind_param("i", $_SESSION["user_id"]);
    		$stmt->execute();
		}

		$result = $stmt->get_result();

		if ($result->num_rows > 0) {
    		while ($row = $result->fetch_assoc()) {
        		echo '<div class="meeting">';
        		echo '<strong>Task #' . htmlspecialchars($row["task_id"]) . '</strong><br>';
        		echo 'Description: ' . htmlspecialchars($row["description"]) . '<br>';
        		echo 'Status: ' . htmlspecialchars($row["status"]) . '<br>';
        		echo 'Digital Completion: ' . ($row["can_complete_digitally"] ? 'Yes' : 'No') . '<br>';
        		echo 'Created: ' . htmlspecialchars($row["created_at"]) . '<br>';

        		if ($_SESSION["role"] === "admin") {
            		echo 'Assigned To: '
                		. htmlspecialchars($row["firstname"] . ' ' . $row["lastname"] . ' | ' . $row["email"] . ' | ' . $row["role"])
                		. '<br>';
        		}

        		echo '</div>';
    		}
		} else {
    		echo "<p>No tasks found.</p>";
		}

		$stmt->close();
		?>
    <div class="nav">
      <div>
        <h1>Task Dashboard</h1>
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

	?>
</div>
</body>
