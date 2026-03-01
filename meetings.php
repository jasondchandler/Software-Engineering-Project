
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

    <div class = "main">
        <br><br>

        <h1>Feed of appointments</h1>

        <h3>Create Meeting Form</h3>
        <form action="create_meeting.php" method="POST">
        
            <input type="hidden" name="meeting_id" value="<?php ?>">

            <div class="mb-3">
                <label class="form-label">Date:</label>
                <input type="date" class="form-control" name="date" value="<?php  ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Time:</label>
                <input type="time" class="form-control" name="time" value="<?php  ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Location:</label>
                <input type="text" class="form-control" name="location" value="<?php ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Duration (mins):</label>
                <input type="number" class="form-control" name="duration" min="1" max="180" value="<?php ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Notes:</label>
                <textarea name="notes" class="form-control"> </textarea>
            </div>

            <button type="submit" class = "btn btn-primary form-control">Create Meeting</button>

        </form>

        <br><br>
        <h3>Edit Meeting Form</h3>
        <form action="update_meeting.php" method="POST">
        
            <input type="hidden" name="meeting_id" value="<?php  ?>">

            <div class="mb-3">
                <label class="form-label">Date:</label>
                <input type="date" class="form-control" name="date" value="<?php  ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Time:</label>
                <input type="time" class="form-control" name="time" value="<?php  ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Location:</label>
                <input type="text" class="form-control" name="location" value="<?php ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Duration (mins):</label>
                <input type="number" class="form-control" name="duration" min="1" max="180" value="<?php ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Notes:</label>
                <textarea name="notes" class="form-control"> </textarea>
            </div>

            <button type="submit" class = "btn btn-primary form-control">Update Meeting</button>

        </form>

    </div>

</body>
