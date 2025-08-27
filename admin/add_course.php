<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}
?>

<?php
include '../includes/db_connection.php';

$msg = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $id = $_POST['course_id'];
  $name = $_POST['course_name'];
  $credit = $_POST['credit_hours'];
  $semester = $_POST['semester'];
  $prereq = $_POST['prereq_course_id'];

  $sql = "INSERT INTO course (CourseID, CourseName, CreditHours, Semester, PreReqCourseID)
          VALUES ('$id', '$name', '$credit', '$semester', '$prereq')";

  if (mysqli_query($conn, $sql)) {
    $msg = "✅ Course added successfully!";
  } else {
    $msg = "❌ Error: " . mysqli_error($conn);
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Add Course</title>
  <link rel="stylesheet" href="../style.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>
  <div class="container py-5">
    <h2>Add Course (Manual)</h2>
    <?php if ($msg): ?>
      <div class="alert"><?= $msg ?></div>
    <?php endif; ?>
    <form method="post">
      <label>Course ID</label><input type="text" name="course_id" required><br>
      <label>Course Name</label><input type="text" name="course_name" required><br>
      <label>Credit Hours</label><input type="number" name="credit_hours" required><br>
      <label>Semester</label><input type="text" name="semester" required><br>
      <label>Prerequisite Course ID (optional)</label><input type="text" name="prereq_course_id"><br>
      <button type="submit">Add Course</button>
    </form>
    <br>
    <a href="dashboard.php">⬅ Back to Dashboard</a>
  </div>
</body>
</html>
