

        <?php 
            session_start();
            if (empty($_SESSION["name"])) {
                header("Location: meetings.php");
                exit;
            }
            require_once("connect.php");
            $meeting_id = $_POST["meeting_id"];
            $stmt = $conn->prepare("UPDATE meetings SET status = 'confirmed' WHERE meeting_id = ?");
            $stmt->bind_param("i", $meeting_id);
            $stmt->execute();
            $stmt->close();
            $conn->close();
            header("Location: meetings.php");
            exit;
        ?>


       