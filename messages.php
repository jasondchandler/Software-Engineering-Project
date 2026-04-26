<!DOCTYPE html>
<?php session_start();
require_once("connect.php");
function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
?>

<html>

    <head>

        <title>Charles Casale - Messages</title>

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

        <?php include "nav.php"; ?>

        <div class="container mb-1">
                <?php 
    if (!empty($_SESSION["create_error"])) {
        echo '<div class="alert alert-danger text-center mt-3" role="alert">';
        echo h($_SESSION["create_error"]);
        echo '</div>';
        unset($_SESSION["create_error"]);
    }

    if (!empty($_SESSION["create_success"])) {
        echo '<div class="alert alert-success text-center mt-3" role="alert">';
        echo h($_SESSION["create_success"]);
        echo '</div>';
        unset($_SESSION["create_success"]);
    }
                ?>

                <br>
  <?php if (allow('create-chat')):
        echo '<button type="button" class="btn btn-dark w-100 mb-3 mt-0" data-bs-toggle="modal" data-bs-target="#createChatForm"> 
      Create Conversation
    </button>';
    endif;?>

    <div class="modal fade" id="createChatForm" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Create Conversation</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="create_conversation_action.php" method="POST">
            
              <label class="form-label">Select a user:</label>
                  <select name="user1" class="form-control" required>
                    <?php
                    $usersResult = $conn->query("SELECT user_id, firstname, lastname, email, role FROM users WHERE role IN ('client', 'paralegal')");
                    while ($userRow = $usersResult->fetch_assoc()) {
                        echo '<option value="' . $userRow["user_id"] . '">'
    . $userRow["firstname"] . ' ' . $userRow["lastname"] . ' | ' . $userRow["email"] . ' | ' . $userRow["role"]
    . '</option>';
                    }
                    ?>
                  </select> <br>

                  <label class="form-label">Select another user:</label>
                  <select name="user2" class="form-control" required>
                    <?php
                    $usersResult = $conn->query("SELECT user_id, firstname, lastname, email, role FROM users WHERE role IN ('client', 'paralegal')");
                    while ($userRow = $usersResult->fetch_assoc()) {
                        echo '<option value="' . $userRow["user_id"] . '">'
    . $userRow["firstname"] . ' ' . $userRow["lastname"] . ' | ' . $userRow["email"] . ' | ' . $userRow["role"]
    . '</option>';
                    }
                    ?>
                  </select> <br>

              <button type="submit" class="btn btn-primary form-control">Create Conversation</button>
            </form>
          </div>
        </div>
      </div>
    </div>


    <br><br><br>

    <?php 
    
    if ($_SESSION["role"] === "client" || $_SESSION["role"] === "paralegal") {
$sql = "
SELECT 
    c.conversation_id,
    u.user_id,
    u.firstname,
    u.lastname,
    u.email
FROM conversations c
JOIN conversation_users cu ON cu.conversation_id = c.conversation_id
JOIN users u ON cu.user_id = u.user_id
  WHERE cu.user_id = ?
ORDER BY c.conversation_id
";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $_SESSION["user_id"]);
  $stmt->execute();
  $result = $stmt->get_result();
} 
elseif ($_SESSION["role"] === "admin") {
$sql = "
SELECT 
    c.conversation_id,
    GROUP_CONCAT(CONCAT(u.firstname, ' ', u.lastname) ORDER BY u.user_id SEPARATOR ' & ') AS users
FROM conversations c
JOIN conversation_users cu ON cu.conversation_id = c.conversation_id
JOIN users u ON cu.user_id = u.user_id
GROUP BY c.conversation_id
ORDER BY c.conversation_id;
";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
}
    ?>

    <?php $count=1; if ($result && $result->num_rows > 0): ?>
    <?php while ($row = $result->fetch_assoc()): ?>
        <div class="meeting">
            <span><strong>Conversation #<?php echo $count; ?></strong></span><br>
      
            <?php 
            if ($_SESSION["role"] === "admin") {
              echo '<span>Participants: ';
              echo  $row["users"] . '</span><br>';
            }
            ?></span>
          <hr>

            <form action="conversation_show.php" method="GET">
    <input type="hidden" name="conversation_id" value="<?php echo $row["conversation_id"]; ?>">
    <button type="submit" class="btn btn-primary w-100 mt-2">Open Conversation</button>
</form>

             <?php 
$show_edit_modal = false;
if (!empty($_SESSION["edit_meeting_error"])) {
    $show_edit_modal = true;
}
?>

        </div>
      <?php $count++;?>
    <?php endwhile; ?>
  <?php else: ?>
    <p>No meetings found.</p>
  <?php endif; ?>








            </div>

    
   <?php
include "footer.php"; ?>

    </body>

</html>