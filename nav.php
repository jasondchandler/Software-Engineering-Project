    <?php $current = basename($_SERVER["PHP_SELF"]); ?>

    <nav id = "navigation" class="navbar navbar-expand-lg fixed-top py-0">
        <div class="container-fluid">
            <ul class="link navbar-nav">
                <li class="m-2">
                    <a class="nav-link link <?php echo ($current == "index.php") ? "active" : ""; ?>" href = "index.php">Home</a>
                </li>
            </ul>
            <button class="navbar-toggler ms-auto m-2" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="link navbar-nav ms-auto">
                    <li class="m-2">
                        <a class="nav-link link <?php echo ($current == "") ? "active" : ""; ?>" href="">Page2</a>
                    </li>
                    <li class="m-2">
                        <a class="nav-link link <?php echo ($current == "") ? "active" : ""; ?>" href="">Page3</a>
                    </li>
                    <li class="m-2">
                        <a class="nav-link link <?php echo ($current == "") ? "active" : ""; ?>" href="">Page4</a>
                    </li>
                    <li class="m-2">
                        <a class="nav-link link <?php echo ($current == "") ? "active" : ""; ?>" href="">Page5</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
