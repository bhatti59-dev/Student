<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

include '../includes/db_connection.php';

// Handle deletion
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM student WHERE StudentID = '$delete_id'");
    header("Location: view_students.php");
    exit();
}

// Pagination setup
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Filter by semester
$filter_semester = isset($_GET['semester']) ? trim($_GET['semester']) : '';
$where = $filter_semester ? "WHERE Semester = '$filter_semester'" : '';

$sql = "SELECT * FROM student $where LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $sql);

// Count for pagination
$total_result = mysqli_query($conn, "SELECT COUNT(*) as total FROM student $where");
$total_row = mysqli_fetch_assoc($total_result);
$total_pages = ceil($total_row['total'] / $limit);
?>
<!DOCTYPE html>
<html>
<head>
  <title>View Students</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
  <h2 class="text-center mb-4">📋 Registered Students</h2>

  <!-- Filter Form -->
  <form method="get" class="d-flex justify-content-center mb-3">
    <input type="text" name="semester" class="form-control w-25 me-2" 
           placeholder="Enter semester (e.g. Fall 2025)" 
           value="<?= htmlspecialchars($filter_semester) ?>">
    <button class="btn btn-primary me-2" type="submit">Filter</button>
    <a href="view_students.php" class="btn btn-secondary">Reset</a>
  </form>

  <!-- Student Table -->
  <table class="table table-bordered text-center">
    <thead class="table-dark">
      <tr>
        <th>Student ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Semester</th>
        <th>Program Name</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php if (mysqli_num_rows($result) > 0): ?>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
          <tr>
            <td><?= htmlspecialchars($row['StudentID']) ?></td>
            <td><?= htmlspecialchars($row['Name']) ?></td>
            <td><?= htmlspecialchars($row['Email']) ?></td>
            <td><?= htmlspecialchars($row['Semester']) ?></td>
            <td><?= htmlspecialchars($row['ProgramName'] ?? 'N/A') ?: 'N/A' ?></td>
            <td>
              <a href="?delete=<?= urlencode($row['StudentID']) ?>" 
                 onclick="return confirm('Delete this student?')" 
                 class="btn btn-sm btn-danger">Delete</a>
            </td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr><td colspan="6">No records found.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>

  <!-- Pagination -->
  <nav>
    <ul class="pagination justify-content-center">
      <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
          <a class="page-link" href="?page=<?= $i ?>&semester=<?= urlencode($filter_semester) ?>"><?= $i ?></a>
        </li>
      <?php endfor; ?>
    </ul>
  </nav>

  <div class="text-center mt-3">
    <a href="dashboard.php" class="btn btn-secondary">⬅ Back to Dashboard</a>
  </div>
</div>
</body>
</html>
