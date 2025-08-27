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
    $filename = $_FILES["course_csv"]["tmp_name"];

    if ($_FILES["course_csv"]["size"] > 0) {
        $file = fopen($filename, "r");

        // Skip header row
        fgetcsv($file);

        while (($row = fgetcsv($file, 10000, ",")) !== FALSE) {
            $course_id   = trim($row[0] ?? '');
            $course_name = trim($row[1] ?? '');
            $credit_hours = trim($row[2] ?? '');
            $semester    = trim($row[3] ?? '');
            $program     = trim($row[4] ?? '');
            $prereq_id   = trim($row[5] ?? '');

            if ($course_id == '' || $course_name == '' || $semester == '' || $program == '') {
                continue; // ✅ Must have semester + program
            }

            // Check duplicate
            $check_stmt = $conn->prepare("SELECT 1 FROM course WHERE CourseID = ?");
            $check_stmt->bind_param("s", $course_id);
            $check_stmt->execute();
            $result = $check_stmt->get_result();

            if ($result->num_rows == 0) {
                // Validate prerequisite
                if ($prereq_id != '') {
                    $check_prereq = $conn->prepare("SELECT 1 FROM course WHERE CourseID = ?");
                    $check_prereq->bind_param("s", $prereq_id);
                    $check_prereq->execute();
                    if ($check_prereq->get_result()->num_rows == 0) {
                        $prereq_id = NULL; // if not found, ignore
                    }
                } else {
                    $prereq_id = NULL;
                }

                // Insert course
                $insert_stmt = $conn->prepare(
                    "INSERT INTO course (CourseID, CourseName, CreditHours, Semester, Program, PrerequisiteCourseID) 
                     VALUES (?, ?, ?, ?, ?, ?)"
                );
                $insert_stmt->bind_param("ssisss", $course_id, $course_name, $credit_hours, $semester, $program, $prereq_id);
                $insert_stmt->execute();
                $inserted++;
            } else {
                $skipped++;
            }
        }
        fclose($file);

        $msg = "✅ Upload Complete. Inserted: $inserted, Skipped (duplicates): $skipped";
    } else {
        $msg = "⚠️ Please choose a CSV file.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Upload Courses</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
  <h2 class="text-center mb-4">📚 Upload Courses</h2>

  <?php if ($msg): ?><div class="alert alert-info text-center"><?= $msg ?></div><?php endif; ?>

  <form method="post" enctype="multipart/form-data" class="text-center">
    <div class="mb-3">
      <input type="file" class="form-control" name="course_csv" accept=".csv" required>
    </div>
    <button type="submit" name="upload" class="btn btn-primary">Upload</button>
  </form>

  <div class="text-center mt-3">
    <a href="view_courses.php" class="btn btn-success btn-sm">View Courses</a>
    <a href="dashboard.php" class="btn btn-secondary btn-sm">Back to Dashboard</a>
  </div>
</div>
</body>
</html>
