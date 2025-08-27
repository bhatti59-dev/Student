<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'teacher') {
    header("Location: ../index.php");
    exit();
}

include '../includes/db_connection.php';

$teacher_id = $_SESSION['ref_id'];
$msg = "";

// Handle grade submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = $_POST['student_id'];
    $course_id  = $_POST['course_id'];
    $grade      = $_POST['grade'];

    // Update or insert into academic_record
    $check = $conn->prepare("SELECT * FROM academic_record WHERE StudentID=? AND CourseID=?");
    $check->bind_param("ss", $student_id, $course_id);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        // Update existing
        $update = $conn->prepare("UPDATE academic_record SET Grade=? WHERE StudentID=? AND CourseID=?");
        $update->bind_param("sss", $grade, $student_id, $course_id);
        $update->execute();
        $msg = "✅ Grade updated successfully.";
    } else {
        // Insert new
        $insert = $conn->prepare("INSERT INTO academic_record (StudentID, CourseID, Grade, Status, CompletionDate) VALUES (?, ?, ?, 'Completed', NOW())");
        $insert->bind_param("sss", $student_id, $course_id, $grade);
        $insert->execute();
        $msg = "✅ Grade assigned successfully.";
    }
}

// Fetch students enrolled in teacher's courses
$sql = "
SELECT e.StudentID, s.Name AS StudentName, e.CourseID, c.CourseName, a.Grade
FROM teacher_courses tc
JOIN course c ON tc.CourseID = c.CourseID
JOIN enrollment e ON e.CourseID = c.CourseID
JOIN student s ON e.StudentID = s.StudentID
LEFT JOIN academic_record a ON e.StudentID = a.StudentID AND e.CourseID = a.CourseID
WHERE tc.TeacherID = ?
ORDER BY c.CourseID, s.StudentID
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $teacher_id);
$stmt->execute();
$students = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Grades</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4">
    <h2 class="mb-4">📝 Manage Grades</h2>
    <a href="dashboard.php" class="btn btn-secondary mb-3">⬅ Back to Dashboard</a>

    <?php if ($msg): ?>
        <div class="alert alert-info"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Student ID</th>
                <th>Student Name</th>
                <th>Course ID</th>
                <th>Course Name</th>
                <th>Grade</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($students->num_rows > 0): ?>
                <?php while ($row = $students->fetch_assoc()): ?>
                    <tr>
                        <form method="post">
                            <td><?= htmlspecialchars($row['StudentID']) ?></td>
                            <td><?= htmlspecialchars($row['StudentName']) ?></td>
                            <td><?= htmlspecialchars($row['CourseID']) ?></td>
                            <td><?= htmlspecialchars($row['CourseName']) ?></td>
                            <td>
                                <input type="text" name="grade" value="<?= htmlspecialchars($row['Grade'] ?? '') ?>" class="form-control" required>
                            </td>
                            <td>
                                <input type="hidden" name="student_id" value="<?= $row['StudentID'] ?>">
                                <input type="hidden" name="course_id" value="<?= $row['CourseID'] ?>">
                                <button type="submit" class="btn btn-sm btn-success">Save</button>
                            </td>
                        </form>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="6" class="text-center">No students enrolled in your courses yet.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
