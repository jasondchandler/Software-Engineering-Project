<!DOCTYPE html>
<?php session_start();
require_once("connect.php");?>
<html>

    <head>

        <title>Charles Casale - Chats</title>

        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
        
        <script src="site.js" defer></script>
        <link rel="stylesheet" href="style.css">
        <link rel="icon" type="image/x-icon" href="files/icon.png">

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" 
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" 
        crossorigin="anonymous"></script>

    </head>

    <body>
    <?php

    include "nav.php";
 if ($_SESSION["role"] !== "admin") {

       $sql = "
        SELECT *
        FROM users u
        JOIN cases c ON c.user_id = u.user_id
        JOIN documents d ON d.case_id = c.case_id
        WHERE u.user_id = ?";

    $params = [$_SESSION["user_id"]];
    $types = "i";

    // if ($search != "") {
    //     $sql .= " AND (u.firstname LIKE ? OR u.lastname LIKE ? OR d.description LIKE ?)";
    //     $like = "%$search%";
    //     $params[] = $like;
    //     $params[] = $like;
    //     $params[] = $like;
    //     $types .= "sss";
    // }

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

            <iframe style="display:none;" id="document<?php echo $count;?>"class="w-100" src="<?php echo "files/".$row["path"];?>"> </iframe><br>
            <button class="btn btn-dark w-100"onclick="showFrame(<?php echo $count;?>)">Show document here</button>
            
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
            data-bs-target="#addUser' . $row['document_id'] . '">
            Add user to document
          </button>';
    echo '</div>';
}

                ?>

                <div class="modal fade" id="addUser<?= $row['document_id'] ?>" 
     data-edit-modal="<?php echo $show_edit_modal ? 'true' : 'false'; ?>" 
     tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Select a user to add: </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="add_user_document.php" method="POST">

                <div class="mb-3">
                  
                  <label class="form-label">Assign To:</label>
                  <select name="user_id" class="form-control" required>
                    <?php
                    $usersResult = $conn->query("SELECT user_id, firstname, lastname, email, role FROM users WHERE role IN ('client', 'paralegal')");
                    while ($userRow = $usersResult->fetch_assoc()) {
                        echo '<option value="' . $userRow["user_id"] . '">'
                            . h($userRow["firstname"] . ' ' . $userRow["lastname"] . ' | ' . $userRow["email"] . ' | ' . $userRow["role"])
                            . '</option>';
                    }
                    ?>
                  </select>
                </div>

                <input type="hidden" name="document_id" value="<?php echo $row['document_id']; ?>">

                <button type="submit" class="btn btn-primary form-control">Give access</button>
              </form>
      </div>
    </div>
  </div>
</div>
            <div class="modal fade" id="deleteDocumentForm<?= $row['document_id'] ?>" 
     data-edit-modal="<?php echo $show_edit_modal ? 'true' : 'false'; ?>" 
     tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Are you sure you want to delete this document?</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="delete_document.php" method="POST">
        <input type="hidden" name="document_id" value="<?php echo $row['document_id']; ?>">

                <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary flex-fill">Delete document</button>
        <a href="cases.php" class="btn btn-secondary flex-fill">Keep document</a></div>
    </form>
      </div>
    </div>
  </div>
</div> </div>
          
      <?php $count++;?>
    <?php endwhile; ?>
  <?php else: ?>
    <p>No documents found.</p>
  <?php endif; ?>
  </div>

        <?php include "nav.php"; ?>

                <?php 
                if (isset($_SESSION["chat_error"])) {

                    echo '<div class="alert alert-danger text-center" role="alert">';
                    echo $_SESSION["chat_error"];
                    echo "</div>";
                    unset($_SESSION["chat_error"]);
                }
                ?>

                <br><br>
            </div>

   <?php
include "footer.php"; ?>

    </body>

</html>