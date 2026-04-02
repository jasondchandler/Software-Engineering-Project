<?php 
  session_start(); 
  require_once("connect.php");
?>
<head>

    <title>Charles Casale - Users</title>

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


  <div class = "main">

    <div class="nav">
      <div>
        <h1>Users Dashboard</h1>
        <div class="small">Welcome back, <?php echo $_SESSION["name"]; ?></div>
      </div>
    </div>

      <?php

      if (empty($_SESSION["user_id"]) || $_SESSION["role"] != "admin") {
        header("Location: login.php");
	      $_SESSION["login_error"] = "Please log in.";
        exit;
      }

      function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

        if (isset($_SESSION["update_user_error"])) {

            echo $_SESSION["update_user_error"];

        }

	?>

  <div class="search_container"> 
    <form class="mb-3" method="GET">
      <input class="form-control mb-2"type=text name="search">
      <button class="btn btn-dark w-100">Search</button>
    </form>

    <a class="btn btn-dark w-100"href="<?= strtok($_SERVER['REQUEST_URI'], '?'); ?>" class="btn btn-secondary">Clear Search</a>
    <?php 


      $search = $_GET["search"] ?? "";
      $where = "
          firstname LIKE ?
          OR lastname LIKE ?";

      $like = "%$search%";

      $sql = "SELECT * FROM USERS";

      $params = [];
      if ($search != "") {
        $sql = $sql . " WHERE $where";
        $params[] = $like;
        $params[] = $like;
        $types = "ss";
      }
      $stmt = $conn->prepare($sql);
      if ($search != "")
        $stmt->bind_param($types, ...$params);
      $stmt->execute();
      $result = $stmt->get_result();

    ?>

  </div>

    <?php $count=1; if ($result && $result->num_rows > 0): ?>
    <?php while ($row = $result->fetch_assoc()): ?>
        <div class="meeting">
            <span>User #<?php echo $count; ?></span><br>
            <span>Name: <?php echo h($row["firstname"] . " " . $row["lastname"]); ?></span><br>
            <span>Phone: <?php echo h($row["phone"] ?: "N/A"); ?></span><br>
            <span>Address: <?php echo h($row["address"] ?: "N/A"); ?></span><br>

            <hr>

            <form action="update_role.php" method="POST" class="mb-2">
                    <input type="hidden" name="user_id" value="<?php echo $row['user_id']; ?>">
                    <label>Role:</label>
                    <select name="role" class="form-control mb-3">
                        <option value="client" <?php if($row['role'] === 'client') echo 'selected'; ?>>Client</option>
                        <option value="paralegal" <?php if($row['role'] === 'paralegal') echo 'selected'; ?>>Paralegal</option>
                    </select>
                    <button type="submit" class="form-control btn btn-primary mt-0">Update</button>
            </form>

            </div>
            <br>
            
            

          <?php $count++; ?>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No users found.</p>
    <?php endif; ?>

</div>
</body>
