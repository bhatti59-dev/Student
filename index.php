<?php
session_start();
include 'includes/db_connection.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Check user in database
    $stmt = $conn->prepare("SELECT * FROM users WHERE Username = ? LIMIT 1");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $row = $result->fetch_assoc()) {
        // ⚠ Plain text check (for testing only).
        // Later replace with password_verify($password, $row['Password']);
        if ($password === $row['Password']) {
            $_SESSION['user_id']   = $row['UserID'];
            $_SESSION['username'] = $row['Username'];
            $_SESSION['role']     = $row['Role'];
            $_SESSION['ref_id']   = $row['RefID'];

            // Redirect based on role
            if ($row['Role'] === 'admin') {
                header("Location: admin/dashboard.php");
            } elseif ($row['Role'] === 'student') {
                header("Location: student/dashboard.php");
            } elseif ($row['Role'] === 'teacher') {
                header("Location: teacher/dashboard.php");
            } elseif ($row['Role'] === 'coordinator') {
                header("Location: coordinator/dashboard.php");
            } else {
                $error = "Unknown role assigned!";
            }
            exit();
        } else {
            $error = "❌ Invalid password!";
        }
    } else {
        $error = "❌ User not found!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Unified Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container d-flex justify-content-center align-items-center" style="height:100vh;">
    <div class="card p-4 shadow" style="width:400px;">
        <h3 class="text-center mb-3">🔐 Login</h3>
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="post">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>
    </div>
</div>
</body>
</html>
