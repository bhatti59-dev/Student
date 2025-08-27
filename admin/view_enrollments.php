<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

include '../includes/db_connection.php';

$msg = "";

// Handle deletion
if (isset($_GET['delete'])) {
    $eid = (int)$_GET['delete'];
    $del = $conn->prepare("DELETE FROM enrollment WHERE EnrollmentID = ?");
    $del->bind_param("i", $eid);
    if ($del->execute()) {
        header("Location: view_enrollments.php?m=" . urlencode("Enrollment #$eid deleted"));
        exit();
    } else {
        $msg = "❌ Error deleting enrollment.";
    }
}

// Check if EnrollmentDate column exists
$colExistsRes = mysqli_query($conn, "SHOW COLUMNS FROM `enrollment` LIKE 'EnrollmentDate'");
if ($colExistsRes && mysqli_num_rows($colExistsRes) > 0) {
    $dateField = "e.EnrollmentDate";
} else {
    $dateField = "NULL AS EnrollmentDate";
}

// Query joining Student + Course (show ProgramName)
$sql = "
SELECT
  e.EnrollmentID,
  {$dateField},
  e.StudentID,
  s.Name AS StudentName,
  s.Email AS StudentEmail,
  s.Semester AS StudentSemester,
  s.ProgramName,
  e.CourseID,
  c.CourseName,
  c.CreditHours
FROM enrollment e
LEFT JOIN student s ON e.StudentID = s.StudentID
LEFT JOIN course c  ON e.CourseID = c.CourseID
ORDER BY e.EnrollmentID DESC
";

$result = mysqli_query($conn, $sql);
if (!$result) {
    die("Query error: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>View Enrollments</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
  <h2 class="mb-4">📄 Student Enrollments</h2>

  <?php if (!empty($_GET['m'])): ?>
    <div class="alert alert-success"><?= htmlspecialchars($_GET['m']) ?></div>
  <?php endif; ?>
  <?php if (!empty($msg)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($msg) ?></div>
  <?php endif; ?>

  <table class="table table-bordered table-striped text-center">
    <thead class="table-dark">
      <tr>
        <th>#</th>
        <th>Enrollment Date</th>
        <th>Student ID</th>
        <th>Student Name</th>
        <th>Email</th>
        <th>Semester</th>
        <th>Program</th>
        <th>Course ID</th>
        <th>Course Name</th>
        <th>Credit Hours</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php if (mysqli_num_rows($result) > 0): ?>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
          <tr>
            <td><?= htmlspecialchars($row['EnrollmentID']) ?></td>
            <td><?= $row['EnrollmentDate'] ? htmlspecialchars($row['EnrollmentDate']) : '—' ?></td>
            <td><?= htmlspecialchars($row['StudentID']) ?></td>
            <td><?= htmlspecialchars($row['StudentName'] ?: 'Unknown') ?></td>
            <td><?= htmlspecialchars($row['StudentEmail'] ?: '—') ?></td>
            <td><?= htmlspecialchars($row['StudentSemester'] ?: '—') ?></td>
            <td><?= htmlspecialchars($row['ProgramName'] ?: '—') ?></td>
            <td><?= htmlspecialchars($row['CourseID']) ?></td>
            <td><?= htmlspecialchars($row['CourseName'] ?: 'Unknown') ?></td>
            <td><?= htmlspecialchars($row['CreditHours'] ?: '-') ?></td>
            <td>
              <a href="view_enrollments.php?delete=<?= urlencode($row['EnrollmentID']) ?>"
                 onclick="return confirm('Delete enrollment #<?= htmlspecialchars($row['EnrollmentID']) ?>?')"
                 class="btn btn-sm btn-danger">Delete</a>
            </td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr><td colspan="11" class="text-center">No enrollments found.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>

  <a href="dashboard.php" class="btn btn-secondary">⬅ Back to Dashboard</a>
</div>
</body>
</html>
