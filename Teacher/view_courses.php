<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'teacher') {
    header("Location: ../index.php");
    exit();
}

$teacher_id = $_SESSION['ref_id']; 
$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html>
<head>
  <title>Teacher Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container text-center py-5">
  <h2 class="mb-4">👨‍🏫 Welcome, <?= htmlspecialchars($username) ?>!</h2>

  <div class="d-grid gap-3 col-6 mx-auto">
    <a href="view_courses.php" class="btn btn-primary">📚 View My Courses</a>
    <a href="manage_grades.php" class="btn btn-success">📝 Manage Grades</a>
    <a href="../logout.php" class="btn btn-danger">🚪 Logout</a>
  </div>
</div>
</body>
</html>
