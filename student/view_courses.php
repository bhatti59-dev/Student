<?php
session_start();
if (!isset($_SESSION['student'])) {
    header("Location: login.php");
    exit();
}

include '../includes/db_connection.php';
$student_id = $_SESSION['student'];
$msg = "";

// Detect if EnrollmentDate column exists
$hasDate = false;
$colCheck = $conn->query("SHOW COLUMNS FROM enrollment LIKE 'EnrollmentDate'");
if ($colCheck && $colCheck->num_rows > 0) {
    $hasDate = true;
}

// Handle enrollment request
if (isset($_GET['enroll'])) {
    $course_id = $_GET['enroll'];

    $check = $conn->prepare("SELECT * FROM enrollment WHERE StudentID = ? AND CourseID = ?");
    $check->bind_param("ss", $student_id, $course_id);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows == 0) {
        if ($hasDate) {
            $stmt = $conn->prepare("INSERT INTO enrollment (StudentID, CourseID, EnrollmentDate) VALUES (?, ?, NOW())");
        } else {
            $stmt = $conn->prepare("INSERT INTO enrollment (StudentID, CourseID) VALUES (?, ?)");
        }
        $stmt->bind_param("ss", $student_id, $course_id);
        $stmt->execute();
        header("Location: view_courses.php?m=" . urlencode("✅ Enrolled in course successfully."));
        exit();
    } else {
        header("Location: view_courses.php?m=" . urlencode("⚠️ You are already enrolled in this course."));
        exit();
    }
}

// Handle unenroll (delete) request
if (isset($_GET['delete'])) {
    $course_id = $_GET['delete'];

    $stmt = $conn->prepare("DELETE FROM enrollment WHERE StudentID = ? AND CourseID = ?");
    $stmt->bind_param("ss", $student_id, $course_id);
    $stmt->execute();
    header("Location: view_courses.php?m=" . urlencode("❌ Unenrolled from course successfully."));
    exit();
}

// Fetch all courses uploaded by admin
$courses = $conn->query("SELECT * FROM course ORDER BY Semester, CourseID");

// Message from redirected actions
if (!empty($_GET['m'])) {
    $msg = $_GET['m'];
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Available Courses</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
    <h2 class="mb-4 text-center">📄 Available Courses</h2>

    <?php if (!empty($msg)): ?>
        <div class="alert alert-info"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Course ID</th>
                <th>Course Name</th>
                <th>Credit Hours</th>
                <th>Semester</th>
                <th>Program</th>
                <th>Prerequisite</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $courses->fetch_assoc()): ?>
                <?php
                // Check if already enrolled
                $check_enrolled = $conn->prepare("SELECT 1 FROM enrollment WHERE StudentID = ? AND CourseID = ?");
                $check_enrolled->bind_param("ss", $student_id, $row['CourseID']);
                $check_enrolled->execute();
                $is_enrolled = $check_enrolled->get_result()->num_rows > 0;
                ?>
                <tr>
                    <td><?= htmlspecialchars($row['CourseID']) ?></td>
                    <td><?= htmlspecialchars($row['CourseName']) ?></td>
                    <td><?= htmlspecialchars($row['CreditHours']) ?></td>
                    <td><?= htmlspecialchars($row['Semester']) ?></td>
                    <td><?= htmlspecialchars($row['Program']) ?></td>
                    <td><?= $row['PrerequisiteCourseID'] ?: "None" ?></td>
                    <td>
                        <?php if ($is_enrolled): ?>
                            <a href="?delete=<?= urlencode($row['CourseID']) ?>" class="btn btn-danger btn-sm">Unenroll</a>
                        <?php else: ?>
                            <a href="?enroll=<?= urlencode($row['CourseID']) ?>" class="btn btn-success btn-sm">Enroll</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <a href="dashboard.php" class="btn btn-secondary mt-3">⬅ Back to Dashboard</a>
</div>
</body>
</html>
