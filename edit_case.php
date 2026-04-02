<?php
session_start();
require_once("connect.php");

if (empty($_GET['case_id'])) {
    header("Location: cases.php");
    exit;
}

$case_id = (int)$_GET['case_id'];
$result = $conn->query("SELECT * FROM cases WHERE case_id=$case_id");
if (!$result || $result->num_rows === 0) {
    header("Location: cases.php");
    exit;
}

$case = $result->fetch_assoc();
?>
<head>
    <title>Edit Case</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Edit Case #<?php echo htmlspecialchars($case['case_id']); ?></h2>
    <form action="update_case_action.php" method="POST">
        <input type="hidden" name="case_id" value="<?php echo $case['case_id']; ?>">

        <label>Title:</label>
        <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($case['title']); ?>" required><br>

        <label>Court:</label>
        <input type="text" name="court" class="form-control" value="<?php echo htmlspecialchars($case['court']); ?>" required><br>

        <label>Type:</label>
        <input type="text" name="type" class="form-control" value="<?php echo htmlspecialchars($case['type']); ?>" required><br>

        <label>Filing Date:</label>
        <input type="date" name="filing_date" class="form-control" value="<?php echo $case['filing_date']; ?>" required><br>

        <label>Status:</label>
        <select name="status" class="form-control" required>
            <option value="Open" <?php if($case['status']=='Open') echo 'selected'; ?>>Open</option>
            <option value="Closed" <?php if($case['status']=='Closed') echo 'selected'; ?>>Closed</option>
            <option value="Pending" <?php if($case['status']=='Pending') echo 'selected'; ?>>Pending</option>
            <option value="Appeal" <?php if($case['status']=='Appeal') echo 'selected'; ?>>Appeal</option>
        </select><br>

        <button type="submit" class="btn btn-primary">Update Case</button>
        <a href="cases.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>