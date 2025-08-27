<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'coordinator') {
    header("Location: ../index.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Coordinator Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5 text-center">
    <h2 class="mb-4">🧑‍💼 Welcome, <?= htmlspecialchars($_SESSION['username']) ?></h2>

    <div class="d-grid gap-3 col-6 mx-auto">
        <a href="view_enrollments.php" class="btn btn-primary">📑 View All Enrollments</a>
        <a href="view_academic_records.php" class="btn btn-success">📊 View Academic Records</a>
        <a href="generate_reports.php" class="btn btn-info text-white">📄 Generate Reports</a>
        <a href="manage_programs.php" class="btn btn-warning">🎓 Manage Programs</a>
<a href="assign_courses.php" class="btn btn-info">📌 Assign Courses to Programs</a>
<a href="/student_portal/logout.php" class="btn btn-danger">Logout</a>

    </div>
</div>
</body>
</html>
