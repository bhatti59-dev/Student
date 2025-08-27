<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}
include '../includes/db_connection.php';

$msg = "";
$inserted = 0;
$skipped = 0;

if (isset($_POST['upload'])) {
    $filename = $_FILES["record_csv"]["tmp_name"];

    if ($_FILES["record_csv"]["size"] > 0) {
        $file = fopen($filename, "r");

        // Skip header
        fgetcsv($file);

        while (($row = fgetcsv($file, 10000, ",")) !== FALSE) {
            $student_id = trim($row[0]);
            $course_id  = trim($row[1]);
            $grade      = trim($row[2]);
            $status     = trim($row[3]);
            $completion = trim($row[4]);

            if ($student_id == "" || $course_id == "") continue;

            // Check if CourseID exists in course table
            $course_check = $conn->prepare("SELECT 1 FROM course WHERE CourseID=?");
            $course_check->bind_param("s", $course_id);
            $course_check->execute();
            $course_result = $course_check->get_result();

            if ($course_result->num_rows == 0) {
                // Skip if CourseID does not exist
                $skipped++;
                continue;
            }

            // Check duplicate (same student+course)
            $check = $conn->prepare("SELECT 1 FROM academic_record WHERE StudentID=? AND CourseID=?");
            $check->bind_param("ss", $student_id, $course_id);
            $check->execute();
            $result = $check->get_result();

            if ($result->num_rows == 0) {
                $insert = $conn->prepare(
                    "INSERT INTO academic_record (StudentID, CourseID, Grade, Status, CompletionDate) 
                     VALUES (?, ?, ?, ?, ?)"
                );
                $insert->bind_param("sssss", $student_id, $course_id, $grade, $status, $completion);
                $insert->execute();
                $inserted++;
            } else {
                $skipped++;
            }
        }
        fclose($file);
        $msg = "✅ Upload Complete. Inserted: $inserted, Skipped (duplicates): $skipped";
    } else {
        $msg = "⚠ Please select a CSV file.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Upload Academic Records</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
  <h2 class="mb-4 text-center">📄 Upload Academic Records</h2>

  <?php if ($msg): ?>
    <div class="alert alert-info text-center"><?= $msg ?></div>
  <?php endif; ?>

  <form method="post" enctype="multipart/form-data" class="text-center">
    <div class="mb-3">
      <input type="file" name="record_csv" class="form-control" accept=".csv" required>
    </div>
    <button type="submit" name="upload" class="btn btn-primary">Upload</button>
  </form>

  <div class="text-center mt-3">
    <a href="view_academic_records.php" class="btn btn-success btn-sm">View Records</a>
    <a href="dashboard.php" class="btn btn-secondary btn-sm">Back to Dashboard</a>
  </div>
</div>
</body>
</html>
