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
    $filename = $_FILES["student_csv"]["tmp_name"];

    if ($_FILES["student_csv"]["size"] > 0) {
        $file = fopen($filename, "r");

        // Skip header row
        fgetcsv($file);

        while (($row = fgetcsv($file, 10000, ",")) !== FALSE) {
            $student_id = trim($row[0] ?? '');
            $name       = trim($row[1] ?? '');
            $email      = trim($row[2] ?? '');
            $password   = trim($row[3] ?? '');
            $semester   = trim($row[4] ?? '');
            $program    = trim($row[5] ?? '');

            if ($student_id == '' || $name == '' || $email == '' || $password == '') continue;

            // Check duplicate StudentID in student table
            $check_stmt = $conn->prepare("SELECT 1 FROM student WHERE StudentID = ?");
            $check_stmt->bind_param("s", $student_id);
            $check_stmt->execute();
            $result = $check_stmt->get_result();

            if ($result->num_rows == 0) {
                // Insert into student table
                $insert_stmt = $conn->prepare(
                    "INSERT INTO student (StudentID, Name, Email, Password, Semester, ProgramName) 
                     VALUES (?, ?, ?, ?, ?, ?)"
                );
                $insert_stmt->bind_param("ssssss", $student_id, $name, $email, $password, $semester, $program);
                $insert_stmt->execute();

                // ✅ Also insert into users table for unified login
                $user_stmt = $conn->prepare(
                    "INSERT IGNORE INTO users (Username, Password, Role, RefID) VALUES (?, ?, 'student', ?)"
                );
                $user_stmt->bind_param("sss", $student_id, $password, $student_id);
                $user_stmt->execute();

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
  <title>Upload Students</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container py-5">
    <h2 class="text-center mb-4">📤 Upload Students</h2>

    <?php if ($msg): ?>
      <div class="alert alert-info text-center"><?= $msg ?></div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data" class="text-center">
      <div class="mb-3">
        <input type="file" class="form-control" name="student_csv" accept=".csv" required>
      </div>
      <button type="submit" name="upload" class="btn btn-primary">Upload</button>
    </form>

    <div class="text-center mt-3">
      <a href="view_students.php" class="btn btn-success btn-sm">View Students</a>
      <a href="dashboard.php" class="btn btn-secondary btn-sm">Back to Dashboard</a>
    </div>
  </div>
</body>
</html>
