

        <?php 
            session_start();
            require_once("connect.php");
            if (empty($_SESSION["name"])) {
                header("Location: meetings.php");
                exit;
            }
            $meeting_id = (int) $_POST["meeting_id"];
            echo $meeting_id;
            $stmt = $conn->prepare("UPDATE meetings SET status = 'complete' WHERE meeting_id = ?");
            $stmt->bind_param("i", $meeting_id);
            $stmt->execute();
            $stmt->close();
            $conn->close();
            header("Location: meetings.php");
            exit;
        ?>


       