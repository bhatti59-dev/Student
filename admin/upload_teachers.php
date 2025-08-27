<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}
include '../includes/db_connection.php';

$msg = "";
$inserted = 0; $skipped = 0;

if (isset($_POST['upload'])) {
    $filename = $_FILES["teacher_csv"]["tmp_name"];
    if ($_FILES["teacher_csv"]["size"] > 0) {
        $file = fopen($filename, "r");
        fgetcsv($file); // skip header

        while (($row = fgetcsv($file, 10000, ",")) !== FALSE) {
            $teacher_id = trim($row[0] ?? '');
            $name       = trim($row[1] ?? '');
            $email      = trim($row[2] ?? '');
            $password   = trim($row[3] ?? '');
            $dept       = trim($row[4] ?? '');

            if ($teacher_id == '' || $name == '' || $email == '') continue;

            // check duplicate
            $check = $conn->prepare("SELECT 1 FROM teacher WHERE TeacherID=?");
            $check->bind_param("s", $teacher_id);
            $check->execute();
            $res = $check->get_result();

            if ($res->num_rows == 0) {
                $insert = $conn->prepare("INSERT INTO teacher (TeacherID, Name, Email, Password, Department) VALUES (?,?,?,?,?)");
                $insert->bind_param("sssss", $teacher_id, $name, $email, $password, $dept);
                $insert->execute();

                // ✅ also insert into users table for login
                $u = $conn->prepare("INSERT INTO users (Username, Password, Role, RefID) VALUES (?, ?, 'teacher', ?)");
                $u->bind_param("sss", $email, $password, $teacher_id);
                $u->execute();

                $inserted++;
            } else {
                $skipped++;
            }
        }
        fclose($file);
        $msg = "✅ Upload Complete. Inserted: $inserted, Skipped: $skipped";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Upload Teachers</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
  <h2 class="mb-4 text-center">📤 Upload Teachers</h2>
  <?php if ($msg): ?><div class="alert alert-info"><?= $msg ?></div><?php endif; ?>
  <form method="post" enctype="multipart/form-data">
    <input type="file" name="teacher_csv" class="form-control mb-3" accept=".csv" required>
    <button type="submit" name="upload" class="btn btn-primary w-100">Upload</button>
  </form>
  <div class="mt-3 text-center">
    <a href="dashboard.php" class="btn btn-secondary btn-sm">⬅ Back to Dashboard</a>
  </div>
</div>
</body>
</html>
