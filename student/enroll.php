<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: ../index.php");
    exit();
}

include '../includes/db_connection.php';
$student_id = $_SESSION['ref_id'];
$msg = "";

// Handle enrollment request
if (isset($_GET['course_id'])) {
    $course_id = $_GET['course_id'];

    $check = $conn->prepare("SELECT * FROM enrollment WHERE StudentID=? AND CourseID=?");
    $check->bind_param("ss", $student_id, $course_id);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows == 0) {
        $stmt = $conn->prepare("INSERT INTO enrollment (StudentID, CourseID) VALUES (?, ?)");
        $stmt->bind_param("ss", $student_id, $course_id);
        $stmt->execute();
        $msg = "✅ Successfully enrolled!";
    } else {
        $msg = "⚠ You are already enrolled in this course.";
    }
}

// Get student's semester and program
$student_query = $conn->prepare("SELECT Semester, Program FROM student WHERE StudentID=?");
$student_query->bind_param("s", $student_id);
$student_query->execute();
$student_data = $student_query->get_result()->fetch_assoc();
$semester = $student_data['Semester'];
$program = $student_data['Program'];

// Fetch only matching courses
$query = $conn->prepare("SELECT * FROM course WHERE Semester=? AND Program=?");
$query->bind_param("ss", $semester, $program);
$query->execute();
$result = $query->get_result();

?>
<!DOCTYPE html>
<html>
<head>
    <title>Enroll in Courses</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4">
    <h2>📝 Enroll in Courses (Semester <?= htmlspecialchars($semester) ?> | Program <?= htmlspecialchars($program) ?>)</h2>
    <a href="dashboard.php" class="btn btn-secondary mb-3">⬅ Back to Dashboard</a>

    <?php if ($msg): ?><div class="alert alert-info"><?= $msg ?></div><?php endif; ?>

    <table class="table table-bordered text-center">
        <thead class="table-dark">
            <tr><th>ID</th><th>Name</th><th>Credits</th><th>Action</th></tr>
        </thead>
        <tbody>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['CourseID']) ?></td>
                    <td><?= htmlspecialchars($row['CourseName']) ?></td>
                    <td><?= htmlspecialchars($row['CreditHours']) ?></td>
                    <td><a href="?course_id=<?= urlencode($row['CourseID']) ?>" class="btn btn-success btn-sm">Enroll</a></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="4">No courses available for your program/semester.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
