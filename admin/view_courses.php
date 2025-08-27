<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}
include '../includes/db_connection.php';

// Handle delete
if (isset($_GET['delete'])) {
    $course_id = $_GET['delete'];
    $del = $conn->prepare("DELETE FROM course WHERE CourseID = ?");
    $del->bind_param("s", $course_id);
    $del->execute();
    header("Location: view_courses.php");
    exit();
}

// Search filter
$search = isset($_GET['search']) ? trim($_GET['search']) : "";
$sql = "SELECT * FROM course";
if ($search != "") {
    $sql .= " WHERE CourseName LIKE '%$search%' OR Semester LIKE '%$search%' OR Program LIKE '%$search%'";
}
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
  <title>View Courses</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
  <h2 class="mb-4">📄 View Courses</h2>

  <form method="get" class="d-flex mb-3">
    <input type="text" name="search" class="form-control me-2" value="<?= htmlspecialchars($search) ?>" placeholder="Search by name, semester, or program">
    <button type="submit" class="btn btn-primary">Search</button>
    <a href="view_courses.php" class="btn btn-secondary ms-2">Reset</a>
  </form>

  <table class="table table-bordered text-center">
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
      <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($row['CourseID']) ?></td>
            <td><?= htmlspecialchars($row['CourseName']) ?></td>
            <td><?= htmlspecialchars($row['CreditHours']) ?></td>
            <td><?= htmlspecialchars($row['Semester']) ?></td>
            <td><?= htmlspecialchars($row['Program']) ?></td>
            <td><?= $row['PrerequisiteCourseID'] ?: 'None' ?></td>
            <td>
              <a href="?delete=<?= urlencode($row['CourseID']) ?>" 
                 class="btn btn-danger btn-sm" 
                 onclick="return confirm('Delete this course?')">Delete</a>
            </td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr><td colspan="7">No courses found.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>

  <div class="text-center mt-3">
    <a href="dashboard.php" class="btn btn-secondary">⬅ Back to Dashboard</a>
  </div>
</div>
</body>
</html>
