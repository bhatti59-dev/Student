<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}
include '../includes/db_connection.php';

// Fetch records with student & course info
$sql = "
SELECT 
    ar.RecordID,
    ar.StudentID,
    s.Name AS StudentName,
    ar.CourseID,
    c.CourseName,
    ar.Grade,
    ar.Status,
    ar.CompletionDate
FROM academic_record ar
LEFT JOIN student s ON ar.StudentID = s.StudentID
LEFT JOIN course c ON ar.CourseID = c.CourseID
ORDER BY ar.RecordID DESC
";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
  <title>View Academic Records</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
  <h2 class="mb-4 text-center">📊 Academic Records</h2>

  <table class="table table-bordered text-center">
    <thead class="table-dark">
      <tr>
        <th>#</th>
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
            <td><?= $row['RecordID'] ?></td>
            <td><?= htmlspecialchars($row['StudentID']) ?></td>
            <td><?= htmlspecialchars($row['StudentName'] ?: 'Unknown') ?></td>
            <td><?= htmlspecialchars($row['CourseID']) ?></td>
            <td><?= htmlspecialchars($row['CourseName'] ?: 'Unknown') ?></td>
            <td><?= htmlspecialchars($row['Grade']) ?></td>
            <td><?= htmlspecialchars($row['Status']) ?></td>
            <td><?= htmlspecialchars($row['CompletionDate']) ?></td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr><td colspan="8">No records found.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>

  <div class="text-center mt-3">
    <a href="dashboard.php" class="btn btn-secondary">⬅ Back to Dashboard</a>
  </div>
</div>
</body>
</html>
