<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}
include '../includes/db_connection.php';

// Handle delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM teacher_courses WHERE ID = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: view_teacher_courses.php?m=" . urlencode("Assignment #$id deleted"));
    exit();
}

// Fetch teacher-course assignments
$sql = "
    SELECT 
        tc.ID,
        t.TeacherID,
        t.Name AS TeacherName,
        c.CourseID,
        c.CourseName
    FROM teacher_courses tc
    LEFT JOIN teacher t ON tc.TeacherID = t.TeacherID
    LEFT JOIN course c  ON tc.CourseID = c.CourseID
    ORDER BY t.TeacherID, c.CourseID
";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
  <title>Teacher Course Assignments</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
  <h2 class="mb-4">👨‍🏫 Teacher Course Assignments</h2>

  <?php if (!empty($_GET['m'])): ?>
    <div class="alert alert-success"><?= htmlspecialchars($_GET['m']) ?></div>
  <?php endif; ?>

  <table class="table table-bordered table-striped">
    <thead class="table-dark">
      <tr>
        <th>#</th>
        <th>Teacher ID</th>
        <th>Teacher Name</th>
        <th>Course ID</th>
        <th>Course Name</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($row['ID']) ?></td>
            <td><?= htmlspecialchars($row['TeacherID']) ?></td>
            <td><?= htmlspecialchars($row['TeacherName'] ?: '—') ?></td>
            <td><?= htmlspecialchars($row['CourseID']) ?></td>
            <td><?= htmlspecialchars($row['CourseName'] ?: '—') ?></td>
            <td>
              <a href="?delete=<?= $row['ID'] ?>" 
                 onclick="return confirm('Delete this assignment?')" 
                 class="btn btn-sm btn-danger">Delete</a>
            </td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr><td colspan="6" class="text-center">No assignments found.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>

  <div class="text-center mt-3">
    <a href="upload_teacher_courses.php" class="btn btn-primary btn-sm">Upload More</a>
    <a href="dashboard.php" class="btn btn-secondary btn-sm">⬅ Back to Dashboard</a>
  </div>
</div>
</body>
</html>
