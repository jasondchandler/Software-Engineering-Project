    <?php 
    $current = basename($_SERVER["PHP_SELF"]); 
    include "role_function.php";
    ?>

    <nav id = "navigation" class="navbar navbar-expand-lg fixed-top py-0 navbar-dark bg-dark">
        <div class="container-fluid">
            
            <div class="d-flex align-items-center">
                <a class = "navbar-brand me-5" href="index.php">Home</a>

                <button class="navbar-toggler d-inline" id="sidebarToggle" onclick="toggleSide()" aria-label="Toggle sidebar" aria-controls="sidebar" aria-expanded="true">
                        <i id = "sideIcon" class="bi bi-arrow-bar-left"></i> 
                </button>

            </div>
            
            <button class="navbar-toggler ms-auto m-2" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="link navbar-nav ms-auto">
                    <?php 
                        if (!isset($_SESSION["user_id"])) {
                            echo '<li class="m-2">
                        <a class="nav-link link" href="signup.php">Sign up</a>
                    </li>
                    <li class="m-2">
                        <a class="nav-link link" href="login.php">Log in</a>
                    </li>';
                        }

                        else {
                        echo '<li class="m-2">
                        <a class="nav-link link" href="logout.php">Log out</a>
                    </li>';
                        }
                    
                    ?>
                </ul>
            </div>
        </div>
    </nav>

    <div id="sidebar" class="sidebar">

        <a class="<?php echo ($current == "meetings.php") ? "active" : ""; ?>" href = "meetings.php">Meetings</a>

    <?php 
        if (allow("view-cases")) {
            echo '<a class="' . ($current == "cases.php" ? "active" : "") . '" href="cases.php">Cases</a>';
        }
    ?>

	<a class="<?php echo ($current == "tasks.php") ? "active" : ""; ?>" href = "tasks.php">Tasks</a>

	<a class="<?php echo ($current == "Documents.php" || $current == "documents.php") ? "active" : ""; ?>" href = "Documents.php">Documents</a>

    <?php 
        if (allow("view-users")) {
            echo '<a class="' . ($current == "users.php" ? "active" : "") . '" href="users.php">Users</a>';
        }
    ?>

    


        <script src = "https://code.jquery.com/jquery-3.6.0.min.js" defer></script>
        <script src = "site.js" defer></script>


    </div>
