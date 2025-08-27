<?php
session_start();
if ($_SESSION['role'] !== 'coordinator') {
    header("Location: ../index.php");
    exit();
}
include '../includes/db_connection.php';

$msg = "";

// Add Program
if (isset($_POST['add'])) {
    $pname = trim($_POST['ProgramName']);
    $desc = trim($_POST['Description']);
    if ($pname != "") {
        $stmt = $conn->prepare("INSERT INTO program (ProgramName, Description) VALUES (?, ?)");
        $stmt->bind_param("ss", $pname, $desc);
        $stmt->execute();
        $msg = "✅ Program added successfully!";
    }
}

// Delete Program
if (isset($_GET['delete'])) {
    $pid = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM program WHERE ProgramID=$pid");
    $msg = "🗑 Program deleted!";
}

// Fetch all programs
$result = mysqli_query($conn, "SELECT * FROM program");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Programs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4">
    <h2>🎓 Manage Programs</h2>
    <a href="dashboard.php" class="btn btn-secondary mb-3">⬅ Back</a>

    <?php if ($msg): ?>
        <div class="alert alert-info"><?= $msg ?></div>
    <?php endif; ?>

    <!-- Add Program Form -->
    <form method="post" class="card p-3 mb-4">
        <h5>Add New Program</h5>
        <div class="mb-2">
            <input type="text" name="ProgramName" class="form-control" placeholder="Program Name" required>
        </div>
        <div class="mb-2">
            <textarea name="Description" class="form-control" placeholder="Description (optional)"></textarea>
        </div>
        <button type="submit" name="add" class="btn btn-success">➕ Add Program</button>
    </form>

    <!-- Program List -->
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Program Name</th>
                <th>Description</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= $row['ProgramID'] ?></td>
                <td><?= htmlspecialchars($row['ProgramName']) ?></td>
                <td><?= htmlspecialchars($row['Description']) ?></td>
                <td>
                    <a href="?delete=<?= $row['ProgramID'] ?>" onclick="return confirm('Delete this program?')" class="btn btn-sm btn-danger">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
