<?php
session_start();
if ($_SESSION['role'] !== 'coordinator') {
    header("Location: ../index.php");
    exit();
}
include '../includes/db_connection.php';

$msg = "";

// Assign course
if (isset($_POST['assign'])) {
    $program_id = $_POST['ProgramID'];
    $course_id = $_POST['CourseID'];

    $check = $conn->prepare("SELECT * FROM program_courses WHERE ProgramID=? AND CourseID=?");
    $check->bind_param("is", $program_id, $course_id);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows == 0) {
        $stmt = $conn->prepare("INSERT INTO program_courses (ProgramID, CourseID) VALUES (?, ?)");
        $stmt->bind_param("is", $program_id, $course_id);
        $stmt->execute();
        $msg = "✅ Course assigned to program!";
    } else {
        $msg = "⚠ Course already assigned.";
    }
}

// Delete mapping
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM program_courses WHERE ID=$id");
    $msg = "🗑 Mapping deleted.";
}

// Fetch programs and courses
$courses = mysqli_query($conn, "SELECT * FROM course");

// Fetch assignments
$assignments = mysqli_query($conn, "
    SELECT pc.ID, p.ProgramName, c.CourseName, c.CourseID
    FROM program_courses pc
    JOIN program p ON pc.ProgramID=p.ProgramID
    JOIN course c ON pc.CourseID=c.CourseID
");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Assign Courses to Programs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4">
    <h2>📌 Assign Courses to Programs</h2>
    <a href="dashboard.php" class="btn btn-secondary mb-3">⬅ Back</a>

    <?php if ($msg): ?>
        <div class="alert alert-info"><?= $msg ?></div>
    <?php endif; ?>

    <!-- Form to assign -->
    <form method="post" class="card p-3 mb-4">
        <h5>Assign Course</h5>
        <div class="row">
            <div class="col-md-5">
                <select name="ProgramID" class="form-control" required>
                    <option value="">Select Program</option>
                    <?php while ($p = mysqli_fetch_assoc($programs)): ?>
                        <option value="<?= $p['ProgramID'] ?>"><?= $p['ProgramName'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="col-md-5">
                <select name="CourseID" class="form-control" required>
                    <option value="">Select Course</option>
                    <?php while ($c = mysqli_fetch_assoc($courses)): ?>
                        <option value="<?= $c['CourseID'] ?>"><?= $c['CourseName'] ?> (<?= $c['CourseID'] ?>)</option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" name="assign" class="btn btn-success w-100">Assign</button>
            </div>
        </div>
    </form>

    <!-- Assigned List -->
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Program</th>
                <th>Course</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = mysqli_fetch_assoc($assignments)): ?>
            <tr>
                <td><?= $row['ID'] ?></td>
                <td><?= htmlspecialchars($row['ProgramName']) ?></td>
                <td><?= htmlspecialchars($row['CourseName']) ?> (<?= $row['CourseID'] ?>)</td>
                <td>
                    <a href="?delete=<?= $row['ID'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete mapping?')">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
