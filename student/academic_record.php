<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: ../index.php");
    exit();
}

include '../includes/db_connection.php';
$student_id = $_SESSION['ref_id'];

// Fetch records uploaded by Admin
$query = $conn->prepare("
    SELECT c.CourseName, a.Grade, a.Status, a.CompletionDate
    FROM academic_record a
    JOIN course c ON a.CourseID = c.CourseID
    WHERE a.StudentID=?
");
$query->bind_param("s", $student_id);
$query->execute();
$result = $query->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Academic Records</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4">
    <h2>📊 My Academic Records</h2>
    <a href="dashboard.php" class="btn btn-secondary mb-3">⬅ Back to Dashboard</a>

    <table class="table table-bordered text-center">
        <thead class="table-dark">
            <tr><th>Course</th><th>Grade</th><th>Status</th><th>Completion Date</th></tr>
        </thead>
        <tbody>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['CourseName'] ?></td>
                    <td><?= $row['Grade'] ?></td>
                    <td><?= $row['Status'] ?></td>
                    <td><?= $row['CompletionDate'] ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="4">No academic records found.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
