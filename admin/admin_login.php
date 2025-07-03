<?php
session_start();
require_once 'Admin.php';
$admin = new Admin();

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if ($admin->login($username, $password)) {
        $_SESSION['admin'] = $username;
header("Location: /student-reg-oops/admin/admin_dashboard.php");
exit;

        exit;
    } else {
        $message = "Invalid credentials!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../style/admin_login.css">


</head>
<body>
    <div class="card col-md-4">
        <div class="card-header">
            Admin Login
        </div>
        <div class="card-body">
            <?php if ($message): ?>
                <div class="alert alert-danger" role="alert">
                    <?= $message ?>
                </div>
            <?php endif; ?>
            <form method="post">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" name="username" id="username" class="form-control" required autocomplete="username">
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" id="password" class="form-control" required autocomplete="current-password">
                </div>
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">Login</button>
                    <a href="index.php" class="btn btn-warning">Home</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>