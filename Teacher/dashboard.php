<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'teacher') {
    header("Location: ../index.php");
    exit();
}

$teacher_id = $_SESSION['ref_id'];
?>

<!DOCTYPE html>
<html>
<head>
  <title>Teacher Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5 text-center">
  <h2 class="mb-4">👨‍🏫 Welcome, Teacher ID: <?= htmlspecialchars($teacher_id) ?></h2>

  <div class="d-grid gap-3 col-6 mx-auto">
    <a href="view_assigned_courses.php" class="btn btn-primary">📚 View Assigned Courses</a>
    <a href="manage_grades.php" class="btn btn-success">📝 Manage Grades</a>
    <a href="view_student_records.php" class="btn btn-info text-white">📊 View Student Records</a>
<a href="/student_portal/logout.php" class="btn btn-danger">Logout</a>
  </div>
</div>
</body>
</html>
