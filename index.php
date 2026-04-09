<!DOCTYPE html>
<?php session_start();?>
<html>

    <head>

        <title>Charles Casale - Home</title>

        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
        
        <script src="site.js" defer></script>
        <link rel="stylesheet" href = "style.css">
        <link rel="icon" type="image/x-icon" href="">

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" 
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" 
        crossorigin="anonymous"></script>

    </head>

    <body>

        <?php include "nav.php"; ?>

        <div id="main" class = "main">

            <div class = "container">
                <h2> Content</h2>
                <p><?php var_dump($_SESSION);?>
                </p>
                <br><br>

                <?php 
                
                if (isset($_SESSION["permission_error"])) {

                    echo '<div class="alert alert-danger text-center" role="alert">';
                    echo $_SESSION["permission_error"];
                    echo "</div>";
                    unset($_SESSION["permission_error"]);
                }
                
                ?>

                <br><br>
            </div>

            <div class = "container d-flex justify-content-center mb-5">
                <div class="row d-flex justify-content-center">
                    <div class="col-4 d-flex justify-content-center columns m-2">
                        Lorem ipsum dolor, sit amet consectetur adipisicing elit. Tenetur temporibus qui dignissimos voluptatum, laboriosam laborum magni quibusdam fugit odio quidem ducimus! Quae iste ab maiores quas ratione quisquam repudiandae reprehenderit.
                    </div>
                    <div class="col-4 d-flex justify-content-center columns m-2">
                        Lorem ipsum dolor sit amet consectetur, adipisicing elit. Neque a quia ratione similique molestias? Enim ratione mollitia ipsam, quo cumque odio distinctio adipisci ducimus placeat et libero, sint, hic reprehenderit?
                    </div>
                    <div class="col-4 d-flex justify-content-center columns m-2">
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Fuga amet, quas quis esse nostrum eligendi qui nemo exercitationem ut eum minima tenetur voluptatibus impedit iusto debitis vel delectus temporibus alias.
                    </div>
                </div>
            </div>

            <div class="info1">

            <h1>Content Class #1</h1>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Ea minima fuga explicabo neque labore, consequatur est optio mollitia consectetur ipsum esse! Maiores a nihil eius repellendus. Rerum totam sapiente aliquid.</p>

            </div>

            <div class="info2">

            <h1>Content Class #2</h1>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Ea minima fuga explicabo neque labore, consequatur est optio mollitia consectetur ipsum esse! Maiores a nihil eius repellendus. Rerum totam sapiente aliquid.</p>

            </div>

        </div>

    </body>

</html>