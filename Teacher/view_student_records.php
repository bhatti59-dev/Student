<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'teacher') {
    header("Location: ../index.php");
    exit();
}

include '../includes/db_connection.php';

$teacher_id = $_SESSION['ref_id'];

// Fetch students' academic records for teacher's courses
$sql = "
SELECT s.StudentID, s.Name AS StudentName, c.CourseID, c.CourseName, 
       ar.Grade, ar.Status, ar.CompletionDate
FROM teacher_courses tc
JOIN course c ON tc.CourseID = c.CourseID
JOIN enrollment e ON e.CourseID = c.CourseID
JOIN student s ON e.StudentID = s.StudentID
LEFT JOIN academic_record ar ON ar.StudentID = s.StudentID AND ar.CourseID = c.CourseID
WHERE tc.TeacherID = ?
ORDER BY c.CourseID, s.StudentID
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $teacher_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <title>View Student Records</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4">
    <h2 class="mb-4">📊 View Student Academic Records</h2>
    <a href="dashboard.php" class="btn btn-secondary mb-3">⬅ Back to Dashboard</a>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Student ID</th>
                <th>Student Name</th>
                <th>Course ID</th>
                <th>Course Name</th>
                <th>Grade</th>
                <th>Status</th>
                <th>Completion Date</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['StudentID']) ?></td>
                        <td><?= htmlspecialchars($row['StudentName']) ?></td>
                        <td><?= htmlspecialchars($row['CourseID']) ?></td>
                        <td><?= htmlspecialchars($row['CourseName']) ?></td>
                        <td><?= htmlspecialchars($row['Grade'] ?? '—') ?></td>
                        <td><?= htmlspecialchars($row['Status'] ?? '—') ?></td>
                        <td><?= htmlspecialchars($row['CompletionDate'] ?? '—') ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="7" class="text-center">No records found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
