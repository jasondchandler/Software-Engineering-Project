<?php
session_start();
include "connect.php";



$stmt = $conn->prepare("
    SELECT 
        cm.message_id, cm.message, cm.created_at, 
        u.user_id, u.firstname, u.lastname, u.email
    FROM conversation_messages cm
    JOIN conversations c on cm.conversation_id = c.conversation_id
    JOIN users u ON cm.sender_id = u.user_id
    WHERE cm.conversation_id = ?
    ORDER BY cm.created_at ASC
");


$conversation_id = $_GET['conversation_id'];
$stmt->bind_param("i", $conversation_id);

$stmt->execute();
$result = $stmt->get_result(); ?>

<!DOCTYPE html>
<?php 
function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
?>

<html>

    <head>

        <title>Charles Casale - Conversation</title>

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

    <br><br>

        <div class="container mb-1">
         <?php   
        while ($row = $result->fetch_assoc()) {

        if ($_SESSION["user_id"] === $row["user_id"]) {

            echo "<div class='message right'><div class='bubble'>";
        echo "<strong>" 
            . htmlspecialchars($row['firstname'] . " " . $row['lastname']) 
            . "</strong> ";

        echo "<small>(" . $row['created_at'] . ")</small><br>";

        echo htmlspecialchars($row['message']);

        echo "</div></div>";
        }

        else  {

            echo "<div class='message left'><div class='bubble'>";
        echo "<strong>" 
            . htmlspecialchars($row['firstname'] . " " . $row['lastname']) 
            . "</strong> ";

        echo "<small>(" . $row['created_at'] . ")</small><br>";

        echo htmlspecialchars($row['message']);

        echo "</div></div>";
        }

    
}

?>

<form action="send_message.php" method="POST">
    <input type="hidden" name="conversation_id" value="<?php echo $conversation_id; ?>">

    <label class="form-label">Type your message: </label> <br>
    <textarea name="message" class="form-control mb-3" required></textarea>

    <button type="submit" class="btn btn-primary form-control">Send</button>
</form>



            </div>

    
   <?php
include "footer.php"; ?>

    </body>

</html>