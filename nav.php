    <?php 
    $current = basename($_SERVER["PHP_SELF"]); 
    include "role_function.php";
    ?>


    <header>
  <a class="logo" href="index.php">⚖ Charles Casale Law</a>
  <nav>
  <?php 
        if (allow("view-meetings")) {
            echo '<a class="' . ($current == "meetings.php" ? "active" : "") . '" href="meetings.php">Meetings</a>';
        }
    ?>
    <?php 
        if (allow("view-cases")) {
            echo '<a class="' . ($current == "cases.php" ? "active" : "") . '" href="cases.php">Cases</a>';
        }
    ?>

    <?php 
        if (allow("view-tasks")) {
            echo '<a class="' . ($current == "tasks.php" ? "active" : "") . '" href="tasks.php">Tasks</a>';
        }
    ?>

	
<?php 
        if (allow("view-documents")) {
            echo '<a class="' . ($current == "Documents.php" ? "active" : "") . '" href="Documents.php">Documents</a>';
        }
    ?>

    <?php 
        if (allow("view-users")) {
            echo '<a class="' . ($current == "users.php" ? "active" : "") . '" href="users.php">Users</a>';
        }
    ?>

    <?php 
        if (allow("view-chats")) {
            echo '<a class="' . ($current == "chats.php" ? "active" : "") . '" href="chats.php">Chats</a>';
        }
    ?>

                        <?php 
                        if (!isset($_SESSION["user_id"])) {
                            echo '<a class="" href="login.php">Log in</a>         ';
                            echo '<a class="" href="signup.php">Sign up</a>';
                        }

                        else {
                        echo '<a class="" href="logout.php">Log out</a>';
                        }
                    
                    ?>
  </nav>
</header>


    


        <script src = "https://code.jquery.com/jquery-3.6.0.min.js" defer></script>
        <script src = "site.js" defer></script>

