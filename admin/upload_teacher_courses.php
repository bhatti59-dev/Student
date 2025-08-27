<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

include '../includes/db_connection.php';

$msg = "";
$inserted = 0;
$skipped_dup = 0;
$skipped_missing_fk = 0;

if (isset($_POST['upload'])) {
    if (!isset($_FILES['teacher_course_csv']) || $_FILES['teacher_course_csv']['error'] !== UPLOAD_ERR_OK) {
        $msg = "⚠️ Please choose a valid CSV file.";
    } else {
        $filename = $_FILES["teacher_course_csv"]["tmp_name"];

        if ($_FILES["teacher_course_csv"]["size"] > 0) {
            $file = fopen($filename, "r");
            if (!$file) {
                $msg = "⚠️ Could not open the uploaded file.";
            } else {
                // Skip header row if present
                // (Assumes the first row is headers; comment this line out if your file has no header)
                fgetcsv($file);

                // Prepare reusable statements
                $checkTeacher = $conn->prepare("SELECT 1 FROM teacher WHERE TeacherID = ?");
                $checkCourse  = $conn->prepare("SELECT 1 FROM course  WHERE CourseID  = ?");
                $checkDup     = $conn->prepare("SELECT 1 FROM teacher_courses WHERE TeacherID = ? AND CourseID = ?");
                $insertStmt   = $conn->prepare("INSERT INTO teacher_courses (TeacherID, CourseID) VALUES (?, ?)");

                while (($row = fgetcsv($file, 10000, ",")) !== false) {
                    // Expected CSV columns: TeacherID, CourseID, (optional) Semester
                    $teacher_id = isset($row[0]) ? trim($row[0]) : "";
                    $course_id  = isset($row[1]) ? trim($row[1]) : "";

                    if ($teacher_id === "" || $course_id === "") {
                        // malformed line; ignore silently
                        continue;
                    }

                    // Check FK: teacher exists?
                    $checkTeacher->bind_param("s", $teacher_id);
                    $checkTeacher->execute();
                    $teacherOK = $checkTeacher->get_result()->num_rows > 0;

                    // Check FK: course exists?
                    $checkCourse->bind_param("s", $course_id);
                    $checkCourse->execute();
                    $courseOK = $checkCourse->get_result()->num_rows > 0;

                    if (!$teacherOK || !$courseOK) {
                        $skipped_missing_fk++;
                        continue;
                    }

                    // Duplicate assignment?
                    $checkDup->bind_param("ss", $teacher_id, $course_id);
                    $checkDup->execute();
                    $isDup = $checkDup->get_result()->num_rows > 0;

                    if ($isDup) {
                        $skipped_dup++;
                        continue;
                    }

                    // Insert assignment
                    $insertStmt->bind_param("ss", $teacher_id, $course_id);
                    if ($insertStmt->execute()) {
                        $inserted++;
                    }
                }

                fclose($file);
                $msg = "✅ Upload complete. Inserted: $inserted, Skipped duplicates: $skipped_dup, Skipped (missing teacher/course): $skipped_missing_fk";
            }
        } else {
            $msg = "⚠️ Empty file uploaded.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Upload Teacher Courses</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container py-5">
    <div class="card shadow p-4 mx-auto" style="max-width: 520px;">
      <h3 class="text-center mb-3">👨‍🏫 Upload Teacher Courses</h3>

      <?php if (!empty($msg)): ?>
        <div class="alert alert-info"><?= htmlspecialchars($msg) ?></div>
      <?php endif; ?>

      <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
          <label class="form-label">CSV File</label>
          <input type="file" class="form-control" name="teacher_course_csv" accept=".csv" required>
          <div class="form-text">
            Expected columns: <b>TeacherID, CourseID</b> (header row allowed).
          </div>
        </div>
        <button type="submit" name="upload" class="btn btn-primary w-100">Upload</button>
      </form>

      <div class="mt-3 text-center">
        <a href="view_teacher_courses.php" class="btn btn-success btn-sm">View Teacher Courses</a>
        <a href="dashboard.php" class="btn btn-secondary btn-sm">Back to Dashboard</a>
      </div>
    </div>
  </div>
</body>
</html>
