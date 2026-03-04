    <?php $current = basename($_SERVER["PHP_SELF"]); ?>

    <nav id = "navigation" class="navbar navbar-expand-lg fixed-top py-0">
        <div class="container-fluid">
            <ul class="link navbar-nav">
                <button class="navbar-toggler d-inline" id="sidebarToggle" onclick="toggleSide()" aria-label="Toggle sidebar" aria-controls="sidebar" aria-expanded="true">
                    <i id = "sideIcon" class="bi bi-arrow-bar-left"></i> 
                </button>
            </ul>
            <button class="navbar-toggler ms-auto m-2" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="link navbar-nav ms-auto">
                    <li class="m-2">
                        <a class="nav-link link <?php echo ($current == "") ? "active" : ""; ?>" href="signup.php">Sign up</a>
                    </li>
                    <li class="m-2">
                        <a class="nav-link link <?php echo ($current == "") ? "active" : ""; ?>" href="login.php">Log in</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div id="sidebar" class="sidebar">

        <a class="<?php echo ($current == "index.php") ? "active" : ""; ?>" href = "index.php">Home</a>
        <a class="<?php echo ($current == "") ? "active" : ""; ?>" href = "">Make meeting</a>
        <a class="<?php echo ($current == "meetings.php") ? "active" : ""; ?>" href = "meetings.php">View meeting</a>
        <a class="<?php echo ($current == "") ? "active" : "" ?>" href = "">Set meeting Times</a>


        <script src = "https://code.jquery.com/jquery-3.6.0.min.js" defer></script>
        <script src = "site.js" defer></script>


    </div>
