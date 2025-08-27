<?php
session_start();
if ($_SESSION['role'] !== 'coordinator') {
    header("Location: ../index.php");
    exit();
}
include '../includes/db_connection.php';

$sql = "SELECT e.EnrollmentID, e.StudentID, s.Name, s.Program, c.CourseID, c.CourseName, e.EnrollmentDate
        FROM enrollment e
        JOIN student s ON e.StudentID = s.StudentID
        JOIN course c ON e.CourseID = c.CourseID
        ORDER BY e.EnrollmentDate DESC";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>View Enrollments</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4">
    <h2>📑 All Enrollments</h2>
    <a href="dashboard.php" class="btn btn-secondary mb-3">⬅ Back</a>

    <table class="table table-bordered table-striped">
        <thead>
            <tr><th>ID</th><th>Student</th><th>Program</th><th>Course</th><th>Date</th></tr>
        </thead>
        <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= $row['EnrollmentID'] ?></td>
                <td><?= $row['StudentID'] ?> - <?= $row['Name'] ?></td>
                <td><?= $row['Program'] ?></td>
                <td><?= $row['CourseID'] ?> - <?= $row['CourseName'] ?></td>
                <td><?= $row['EnrollmentDate'] ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
