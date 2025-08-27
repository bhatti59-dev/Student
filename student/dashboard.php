<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: ../index.php");
    exit();
}
$student_id = $_SESSION['ref_id'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Student Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5 text-center">
    <h2 class="mb-4">🎓 Welcome, Student ID: <?= htmlspecialchars($student_id) ?></h2>

    <div class="d-grid gap-3 col-6 mx-auto">
        <a href="enroll.php" class="btn btn-success">📝 Enroll in Courses</a>
        <a href="my_courses.php" class="btn btn-primary">📋 My Enrolled Courses</a>
        <a href="academic_record.php" class="btn btn-info text-white">📊 My Academic Records</a>
<a href="/student_portal/logout.php" class="btn btn-danger">Logout</a>
    </div>
</div>
</body>
</html>
