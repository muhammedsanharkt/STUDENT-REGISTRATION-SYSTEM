<?php
session_start();
require_once '../user/student.php';


$student = new Student();
$message = "";
$adminMessage = "";

// Handle student registration
if (isset($_POST['register'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $course = trim($_POST['course']);
    $number = trim($_POST['number']);

    if (empty($name) || empty($email) || empty($course) || empty($number)) {
        $message = "All fields are required!";
    } elseif (!preg_match("/^[a-zA-Z\s]+$/", $name)) {
        $message = "Name must contain letters only!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email format!";
    } elseif ($course == "Select") {
        $message = "Please select a course!";
    } elseif (!preg_match("/^[0-9]{1,10}$/", $number)) {
        $message = "Phone number must contain digits only (max 10)!";
    } else {
        if ($student->register($name, $email, $course, $number)) {
            $message = "Student registered successfully!";
        } else {
            $message = "Error: Email must be unique.";
        }
    }
}

// Handle delete request
if (isset($_GET['delete']) && isset($_SESSION['admin'])) {
    $student->delete($_GET['delete']);
header("Location: /student-reg-oops/admin/admin_dashboard.php#students");
    exit;
}

// Handle add admin
if (isset($_POST['add_admin']) && isset($_SESSION['admin'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $adminMessage = "All fields are required!";
    } else {
        if ($student->addAdmin($username, $password)) {
            $adminMessage = "New admin added successfully!";
        } else {
            $adminMessage = "Error: Username must be unique.";
        }
    }
}

$students = $student->getAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Management Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style/admin_dashboard.css">
</head>
<body>
<div class="dashboard-container">
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h4><i class="fas fa-graduation-cap"></i> EduManage</h4>
            <div class="subtitle">Student Management System</div>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-item">
                <a href="#" class="nav-link" onclick="showSection('registration')">
                    <i class="fas fa-user-plus"></i> Student Registration
                </a>
            </div>
            <div class="nav-item">
                <a href="#" class="nav-link" onclick="showSection('students')">
                    <i class="fas fa-users"></i> Registered Students
                </a>
            </div>
            <?php if (isset($_SESSION['admin'])): ?>
            <div class="nav-item">
                <a href="#" class="nav-link" onclick="showSection('add-admin')">
                    <i class="fas fa-user-cog"></i> Add Admin
                </a>
            </div>
            <?php endif; ?>
        </nav>

        <div class="admin-section">
            <?php if (isset($_SESSION['admin'])): ?>
                <div class="admin-info">
                    <div class="admin-avatar">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <div>
                        <div style="font-weight: 500;">Admin Mode</div>
                        <small style="color: #94a3b8;">Full Access</small>
                    </div>
                </div>
                <a href="logout.php" class="btn btn-outline-light btn-sm w-100">
                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                </a>
            <?php else: ?>
                <a href="admin_login.php" class="btn btn-outline-light btn-sm w-100">
                    <i class="fas fa-sign-in-alt me-2"></i>Admin Login
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Student Registration Section -->
        <div id="registration" class="content-section">
            <div class="page-header">
                <h2><i class="fas fa-user-plus me-2"></i>Student Registration</h2>
                <p>Register new students in the system</p>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-form me-2"></i>Registration Form</h5>
                </div>
                <div class="card-body">
                    <form method="post">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><i class="fas fa-user me-1"></i>Full Name</label>
                                <input type="text" name="name" class="form-control" placeholder="Enter student name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><i class="fas fa-envelope me-1"></i>Email Address</label>
                                <input type="email" name="email" class="form-control" placeholder="Enter email address" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><i class="fas fa-book me-1 mt-2"></i>Course</label>
                                <select name="course" class="form-select" required>
                                    <option value="Select">-- Select Course --</option>
                                    <option value="Mathematics">Mathematics</option>
                                    <option value="Science">Science</option>
                                    <option value="History">History</option>
                                    <option value="Computer Science">Computer Science</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><i class="fas fa-phone me-1"></i>Phone Number</label>
                                <input type="text" name="number" class="form-control" placeholder="Enter phone number" required>
                            </div>
                        </div>
                        <div class="text-end">
                            <button name="register" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Register Student
                            </button>
                        </div>
                        <?php if ($message): ?>
                            <p style="margin-top: 15px; color: <?= strpos($message, 'successfully') !== false ? 'green' : 'red'; ?>">
                                <?= htmlspecialchars($message) ?>
                            </p>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>

        <!-- Registered Students Section -->
        <div id="students" class="content-section">
            <div class="page-header">
                <h2><i class="fas fa-users me-2"></i>Registered Students</h2>
                <p>View and manage all registered students</p>
            </div>



            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-table me-2"></i>Students List</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                            <tr>
                                <th><i class="fas fa-hashtag me-1"></i>ID</th>
                                <th><i class="fas fa-user me-1"></i>Name</th>
                                <th><i class="fas fa-envelope me-1"></i>Email</th>
                                <th><i class="fas fa-book me-1"></i>Course</th>
                                <th><i class="fas fa-phone me-1"></i>Number</th>
                                <?php if (isset($_SESSION['admin'])): ?>
                                    <th><i class="fas fa-cogs me-1"></i>Actions</th>
                                <?php endif; ?>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (empty($students)): ?>
                                <tr>
                                    <td colspan="<?= isset($_SESSION['admin']) ? '6' : '5' ?>" class="text-center py-4">
                                        <i class="fas fa-users-slash fs-3 text-muted mb-2"></i>
                                        <p class="text-muted mb-0">No students registered yet</p>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($students as $s): ?>
                                    <tr>
                                        <td><span class="badge bg-primary"><?= htmlspecialchars($s['id']) ?></span></td>
                                        <td><?= htmlspecialchars($s['name']) ?></td>
                                        <td><?= htmlspecialchars($s['email']) ?></td>
                                        <td><span class="badge bg-info"><?= htmlspecialchars($s['course']) ?></span></td>
                                        <td><?= htmlspecialchars($s['number']) ?></td>
                                        <?php if (isset($_SESSION['admin'])): ?>
                                            <td>
                                                
                                            <button class="btn btn-danger btn-sm delete-btn" data-id="<?= $s['id'] ?>">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            </td>
                                        <?php endif; ?>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Admin Section -->
        <?php if (isset($_SESSION['admin'])): ?>
        <div id="add-admin" class="content-section">
            <div class="page-header">
                <h2><i class="fas fa-user-shield me-2"></i>Add New Admin</h2>
                <p>Create additional admin accounts</p>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-user-cog me-2"></i>Add Admin Form</h5>
                </div>
                <div class="card-body">
                    <form method="post">
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" placeholder="Enter username" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Enter password" required>
                        </div>
                        <div class="text-end">
                            <button name="add_admin" class="btn btn-secondary">
                                <i class="fas fa-plus me-2"></i>Add Admin
                            </button>
                        </div>
                        <?php if ($adminMessage): ?>
                            <p style="margin-top: 15px; color: <?= strpos($adminMessage, 'successfully') !== false ? 'green' : 'red'; ?>">
                                <?= htmlspecialchars($adminMessage) ?>
                            </p>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>
        <?php endif; ?>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function showSection(sectionId) {
        const sections = document.querySelectorAll('.content-section');
        sections.forEach(section => section.classList.remove('active'));
        document.getElementById(sectionId).classList.add('active');

        const navLinks = document.querySelectorAll('.nav-link');
        navLinks.forEach(link => link.classList.remove('active'));
        event.target.closest('.nav-link').classList.add('active');
    }

    window.addEventListener('DOMContentLoaded', function () {
        if (window.location.hash === '#students') {
            showSection('students');
        } else {
            showSection('registration');
        }
    });
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('.delete-btn').click(function() {
        

        const id = $(this).data('id');
        const row = $(this).closest('tr');

        $.ajax({
            url: 'delete_student.php',
            type: 'POST',
            data: { id: id },
            success: function(res) {
                if (res.success) {
                    row.fadeOut(500, function() { $(this).remove(); });
                } else {
                    alert('Delete failed!');
                }
            },
            error: function() {
                alert('AJAX failed!');
            }
        });
    });
});
</script>

</body>
</html>
