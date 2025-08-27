<?php
session_start();
if ($_SESSION['role'] !== 'coordinator') {
    header("Location: ../index.php");
    exit();
}
include '../includes/db_connection.php';

$sql = "SELECT ar.RecordID, ar.StudentID, s.Name, c.CourseName, ar.Grade, ar.Status, ar.CompletionDate
        FROM academic_record ar
        JOIN student s ON ar.StudentID = s.StudentID
        JOIN course c ON ar.CourseID = c.CourseID
        ORDER BY ar.CompletionDate DESC";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>View Academic Records</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4">
    <h2>📊 Academic Records</h2>
    <a href="dashboard.php" class="btn btn-secondary mb-3">⬅ Back</a>

    <table class="table table-bordered table-striped">
        <thead>
            <tr><th>ID</th><th>Student</th><th>Course</th><th>Grade</th><th>Status</th><th>Completion Date</th></tr>
        </thead>
        <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= $row['RecordID'] ?></td>
                <td><?= $row['StudentID'] ?> - <?= $row['Name'] ?></td>
                <td><?= $row['CourseName'] ?></td>
                <td><?= $row['Grade'] ?></td>
                <td><?= $row['Status'] ?></td>
                <td><?= $row['CompletionDate'] ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
