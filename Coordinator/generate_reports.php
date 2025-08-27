<?php
session_start();
if ($_SESSION['role'] !== 'coordinator') {
    header("Location: ../index.php");
    exit();
}
include '../includes/db_connection.php';

// Example: total enrollments
$total_enrollments = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM enrollment"))['total'];

// Example: average GPA (if grades A=4, B=3, etc.)
$gpa_sql = "SELECT AVG(
                CASE Grade 
                    WHEN 'A' THEN 4 
                    WHEN 'B' THEN 3 
                    WHEN 'C' THEN 2 
                    WHEN 'D' THEN 1 
                    ELSE 0 
                END
            ) AS avg_gpa 
            FROM academic_record";
$avg_gpa = mysqli_fetch_assoc(mysqli_query($conn, $gpa_sql))['avg_gpa'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Reports</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4">
    <h2>📄 Coordinator Reports</h2>
    <a href="dashboard.php" class="btn btn-secondary mb-3">⬅ Back</a>

    <div class="card p-3 mb-3">
        <h4>Total Enrollments: <?= $total_enrollments ?></h4>
        <h4>Average GPA: <?= number_format($avg_gpa, 2) ?></h4>
    </div>
</div>
</body>
</html>
