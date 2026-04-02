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

  <?php 
    include "nav.php";
    include "role_function.php";?>


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


	<button class="btn btn-dark flex-fill mb-3 mt-3 w-100" data-bs-toggle="modal" data-bs-target="#uploadDocument">Upload Document</button>
	
  <div class="search_container"> 
    <form class="mb-3" method="GET">
      <input class="form-control mb-2"type=text name="search">
      <button class="btn btn-dark w-100">Search</button>
    </form>

    <a class="btn btn-dark w-100"href="<?= strtok($_SERVER['REQUEST_URI'], '?'); ?>" class="btn btn-secondary">Clear Search</a>

    <?php 
      $search = $_GET["search"] ?? "";
      $where = "
           name LIKE ?
          OR description LIKE ?
          OR title LIKE ?";

      $like = "%$search%";

    ?>

  </div>


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

          <?php 
            $case_sql = "SELECT case_id, title FROM cases ORDER BY title ASC";
            $case_result = $conn->query($case_sql);
          ?>

          <label class="form-label">Optionally select a case:</label>
          <select class="form-control mb-3" name="case_id">
              <option value="">-- No case selected --</option>
              <?php while ($case = $case_result->fetch_assoc()): ?>
                  <option value="<?= htmlspecialchars($case['case_id']); ?>">
                      <?= htmlspecialchars($case['title']); ?>
                  </option>
              <?php endwhile; ?>
          </select>

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

      <?php

          if ($_SESSION["role"] === "client") {
  $sql = "
  SELECT *
  FROM documents d
  LEFT JOIN cases c on d.case_id = c.case_id";

  $params = [];
  if ($search != "") {
    $sql = $sql . "WHERE $where";
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
    $types = "sss";
  }
  $stmt = $conn->prepare($sql);
  if ($search != "")
    $stmt->bind_param($types, ...$params);
  $stmt->execute();
  $result = $stmt->get_result();
} 
elseif ($_SESSION["role"] === "admin") {

    $sql = "
    SELECT *
    FROM documents d
    LEFT JOIN cases c on c.case_id = d.case_id
    ";
  $params = [];
  if ($search != "") {
    $sql = $sql . "WHERE $where";
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
    $types = "sss";
  }
  $stmt = $conn->prepare($sql);
  if ($search != "")
    $stmt->bind_param($types, ...$params);
  $stmt->execute();
  $result = $stmt->get_result();
}
?>

  <?php $count=1; if ($result && $result->num_rows > 0): ?>
    <?php while ($row = $result->fetch_assoc()): ?>
        <div class="meeting">
            <span>Document #<?php echo $count; ?></span><br>
            <span>Document name: <?php echo $row["name"];?></span>
            <span><?php 
              if (isset($row["case_id"])) {
                echo "Related case: " . $row["title"];
              }
            ?></span> <br>
            <span>
              <?php  
                if (isset($row["description"]) && $row["description"] !== null && $row["description"] !== "") {
                  echo "Description: ".$row["description"];
                }
              ?>
            </span><br>

            <iframe style="display:none;" id="document<?php echo $count;?>"class="w-100" src="<?php echo "files/".$row["path"];?>"> </iframe><br>
            <button class="btn btn-dark w-100"onclick="showFrame(<?php echo $count;?>)">Show document here</button>
            
            <br><br>
            <a class="btn btn-dark w-100"href="<?php echo "files/".$row["path"];?>" target="_blank">
            Open in new tab
            </a>

          
        </div>
      <?php $count++;?>
    <?php endwhile; ?>
  <?php else: ?>
    <p>No meetings found.</p>
  <?php endif; ?>
  </div>



</div>
</body>
