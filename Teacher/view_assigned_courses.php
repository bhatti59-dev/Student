<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'teacher') {
    header("Location: ../index.php");
    exit();
}
include '../includes/db_connection.php';

$teacher_id = $_SESSION['ref_id'];

// Fetch courses assigned to this teacher
$sql = "SELECT c.CourseID, c.CourseName, c.Semester, c.Program 
        FROM course c
        JOIN teacher_courses tc ON c.CourseID = tc.CourseID
        WHERE tc.TeacherID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $teacher_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Assigned Courses</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
    <h2 class="mb-4">📚 My Assigned Courses</h2>
    <a href="dashboard.php" class="btn btn-secondary mb-3">⬅ Back to Dashboard</a>

    <table class="table table-bordered text-center">
        <thead class="table-dark">
            <tr>
                <th>Course ID</th>
                <th>Course Name</th>
                <th>Semester</th>
                <th>Program</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['CourseID']) ?></td>
                        <td><?= htmlspecialchars($row['CourseName']) ?></td>
                        <td><?= htmlspecialchars($row['Semester']) ?></td>
                        <td><?= htmlspecialchars($row['Program']) ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="4">No courses assigned.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
