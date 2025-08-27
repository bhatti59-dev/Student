<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: ../index.php");
    exit();
}

include '../includes/db_connection.php';
$student_id = $_SESSION['ref_id'];

// Fetch enrolled courses
$query = $conn->prepare("
    SELECT c.CourseID, c.CourseName, c.CreditHours, c.Semester
    FROM enrollment e
    JOIN course c ON e.CourseID = c.CourseID
    WHERE e.StudentID=?
");
$query->bind_param("s", $student_id);
$query->execute();
$result = $query->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Enrollments</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4">
    <h2>📄 My Enrolled Courses</h2>
    <a href="dashboard.php" class="btn btn-secondary mb-3">⬅ Back to Dashboard</a>

    <table class="table table-bordered text-center">
        <thead class="table-dark">
            <tr><th>ID</th><th>Name</th><th>Credits</th><th>Semester</th></tr>
        </thead>
        <tbody>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['CourseID'] ?></td>
                    <td><?= $row['CourseName'] ?></td>
                    <td><?= $row['CreditHours'] ?></td>
                    <td><?= $row['Semester'] ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="4">You are not enrolled in any course.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
