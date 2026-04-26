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
    <link rel="icon" type="image/x-icon" href="files/icon.png">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" 
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" 
    crossorigin="anonymous"></script>

</head>

<body>

  <?php 
    include "nav.php";?>
<div class="container">

    <div class="nav">
      <div>
        <h1>Document Dashboard</h1>
        <div class="small">Welcome back, <?php echo $_SESSION["name"]; ?></div>
      </div>
    </div>

  <?php 
  if ($_SESSION["role"] !== "admin") {

    $sql = "
    SELECT COUNT(d.document_id) AS total_documents
    FROM users u
    JOIN cases c ON c.user_id = u.user_id
    JOIN documents d ON d.case_id = c.case_id
    WHERE u.user_id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION["user_id"]);
$stmt->execute();

$result = $stmt->get_result();
$row = $result->fetch_assoc();

$documentCount = $row["total_documents"] ?? 0;

$stmt->close();
  }

  else {

$sql = "
    SELECT COUNT(d.document_id) AS total_documents
    FROM documents d;
";
$stmt = $conn->prepare($sql);
$stmt->execute();

$result = $stmt->get_result();
$row = $result->fetch_assoc();

$documentCount = $row["total_documents"] ?? 0;

$stmt->close();

  }
  ?>

    <div class="stats  mb-3">
            <div class="stat">
            <div class="small">Document count</div>
            <div class="num fs-4"><?php echo $documentCount; ?></div>
            <div class="small">In system</div>
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


	<?php if (allow("upload-document")): ?>
    <button class="btn btn-dark flex-fill mb-3 mt-3 w-100"
        data-bs-toggle="modal"
        data-bs-target="#uploadDocument">
        Upload Document
    </button>
<?php endif; ?>
	
  <div class="search_container"> 
    <form class="mb-3" method="GET">
      <input class="form-control mb-3"type=text name="search" placeholder="Enter search term...">
      <button class="btn btn-primary w-100">Search</button>
    </form>

    <a class="btn btn-primary w-100"href="<?= strtok($_SERVER['REQUEST_URI'], '?'); ?>">Clear Search</a>


        <hr><br>
    <h1>Your documents:</h1>

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
          <input type="text" class="form-control mb-2" name="description">

            <hr>
	  <button type="submit" class="form-control btn btn-primary mt-2">Upload</button>
        </form>
                    </div>

                  </div>
                </div>
              </div>

      <?php

          if ($_SESSION["role"] === "client" || $_SESSION["role"] === "paralegal") {

    $search = $_GET["search"] ?? "";

    $sql = "
        SELECT *
        FROM users u
        JOIN cases c ON c.user_id = u.user_id
        JOIN documents d ON d.case_id = c.case_id
        WHERE u.user_id = ?
    ";

    $params = [$_SESSION["user_id"]];
    $types = "i";

    if ($search != "") {
        $sql .= " AND (u.firstname LIKE ? OR u.lastname LIKE ? OR d.description LIKE ?)";
        $like = "%$search%";
        $params[] = $like;
        $params[] = $like;
        $params[] = $like;
        $types .= "sss";
    }

    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);

    $stmt->execute();
    $result = $stmt->get_result();
}
elseif ($_SESSION["role"] === "admin") {

$search = $_GET["search"] ?? "";
      $where = "
           name LIKE ?
          OR description LIKE ?";

      $like = "%$search%";

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
    $types = "ss";
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

            <span class="name">Document name: <?php echo $row["name"];?></span>
            <span><?php 
              if (isset($row["case_id"])) {
                echo "<br>Related case: " . $row["title"];
              }
            ?></span> <br>
            <span>
              <?php  
                if (isset($row["description"]) && $row["description"] !== null && $row["description"] !== "") {
                  echo "Description: ".$row["description"];
                }
              ?>
            </span><br>

            <iframe style="display:none;" id="document<?php echo $count; ?>" class="w-100" src="<?php echo "files/".$row["path"]; ?>"></iframe><br>

            <button class="btn btn-dark w-100"
                    onclick="showFrame(<?php echo $count; ?>, this)">
                Show document here
            </button>
            
            <br>
            <a class="btn btn-dark w-100 mt-3"href="<?php echo "files/".$row["path"];?>" target="_blank">
            Open in new tab
            </a>

            <br><hr>
                
                <?php 
                
                if (allow("delete-document")) {
    echo '<div class="d-flex gap-2">';
    echo '<button class="btn btn-danger flex-fill" 
            data-bs-toggle="modal" 
            data-bs-target="#deleteDocumentForm' . $row['document_id'] . '">
            Delete document
          </button>';
    echo '</div><br>';
  }

  if (allow("edit-document-user")) {
    echo '<div class="d-flex gap-2">';
    echo '<button class="btn btn-primary flex-fill" 
            data-bs-toggle="modal" 
            data-bs-target="#grant' . $row['document_id'] . '">
            Add user to document
          </button>';

      $userResult = $conn->query("
    SELECT * 
    FROM document_users du 
    JOIN users u ON du.user_id = u.user_id 
    WHERE document_id = " . $row['document_id']);

    $rowCount = $userResult->num_rows;

    if ($rowCount > 0) {
      echo '<button class="btn btn-danger flex-fill" 
            data-bs-toggle="modal" 
            data-bs-target="#remove' . $row['document_id'] . '">
            Remove user from document
          </button>';
    }
    echo '</div>';
}

                ?>
    

        <div class="modal fade" id="grant<?php echo $row['document_id']; ?>">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5>Grant Access</h5>
                        <button class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <form action="add_document_user.php" method="POST">
                            <input type="hidden" name="document_id" value="<?php echo $row['document_id']; ?>">

                            <select name="user_id" class="form-control mb-2" required>
                                <?php
                                $users = $conn->query("SELECT user_id, firstname, lastname FROM users");
                                while ($u = $users->fetch_assoc()) {
                                    echo "<option value='{$u["user_id"]}'>" .
                                        h($u["firstname"] . " " . $u["lastname"]) .
                                        "</option>";
                                }
                                ?>
                            </select>

                            <button class="btn btn-primary w-100">Grant</button>
                        </form>
                    </div>

                </div>
            </div>
        </div>

        <div class="modal fade" id="remove<?php echo $row['document_id']; ?>">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5>Remove Access</h5>
                        <button class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <form action="remove_document_user.php" method="POST">
                            <input type="hidden" name="document_id" value="<?php echo $row['document_id']; ?>">

                           <select name="user_id" class="form-control mb-2" required>
                                <?php
                                $users = $conn->query("SELECT * FROM document_users du join users u on du.user_id = u.user_id WHERE document_id = " . $row['document_id']);
                                while ($u = $users->fetch_assoc()) {
                                    echo "<option value='{$u["user_id"]}'>" .
                                        h($u["firstname"] . " " . $u["lastname"]) .
                                        "</option>";
                                }
                                ?>
                            </select>

                            <button class="btn btn-danger w-100">Remove</button>
                        </form>
                    </div>

                </div>
            </div>
        </div></div>
          
      <?php $count++;?>
    <?php endwhile; ?>
  <?php else: ?>
    <p>No documents found.</p>
  <?php endif; ?>
  </div>
</div>

  <?php
include "footer.php"; ?>
</body>
