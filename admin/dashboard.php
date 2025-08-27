<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Admin Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container text-center py-5">
    <h2 class="mb-4">👨‍💼 Welcome, <?= $_SESSION['username']; ?>!</h2>

    <div class="list-group col-6 mx-auto">
      <a href="upload_students.php" class="list-group-item list-group-item-action">📤 Upload Students</a>
      <a href="upload_courses.php" class="list-group-item list-group-item-action">📚 Upload Courses</a>
      <a href="upload_academic_record.php" class="list-group-item list-group-item-action">📄 Upload Academic Records</a>
      <a href="upload_teacher_courses.php" class="list-group-item list-group-item-action">👨‍🏫 Upload Teacher Courses</a>

      <a href="view_students.php" class="list-group-item list-group-item-action">👁 View Students</a>
      <a href="view_courses.php" class="list-group-item list-group-item-action">👁 View Courses</a>
      <a href="view_enrollments.php" class="list-group-item list-group-item-action">📑 View Enrollments</a>
      <a href="view_academic_records.php" class="list-group-item list-group-item-action">📊 View Academic Records</a>
      <a href="view_teacher_courses.php" class="list-group-item list-group-item-action">👨‍🏫 View Teacher Courses</a>
    <a href="/student_portal/logout.php" class="btn btn-danger">Logout</a>

    </div>
  </div>
</body>
</html>
