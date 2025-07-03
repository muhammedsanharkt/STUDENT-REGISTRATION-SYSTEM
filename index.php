<?php
session_start();
require_once 'user/student.php';

$student = new Student();
$students = $student->getAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>EduManage - Student Registration</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
  <style>
    body {
      background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
      min-height: 100vh;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .main-card {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      border: none;
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
    }
    .card-header {
      background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%) !important;
      color: white !important;
      border: none !important;
    }
    .btn-primary {
      background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
      border: none;
      padding: 12px 30px;
      font-weight: 600;
      transition: all 0.3s ease;
    }
    .btn-primary:hover {
      background: linear-gradient(135deg, #2a5298 0%, #1e3c72 100%);
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(30, 60, 114, 0.4);
    }
    .form-control, .form-select {
      border: 2px solid #e3e8ef;
      border-radius: 10px;
      padding: 12px 15px;
      transition: all 0.3s ease;
    }
    .form-control:focus, .form-select:focus {
      border-color: #2a5298;
      box-shadow: 0 0 0 0.2rem rgba(42, 82, 152, 0.25);
    }
    .form-label {
      font-weight: 600;
      color: #2c3e50;
      margin-bottom: 8px;
    }
    .error-msg {
      color: #dc3545;
      font-size: 0.875em;
      margin-top: 5px;
      display: block;
    }
    .table {
      background: white;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
    }
    .table thead th {
      background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
      color: white;
      border: none;
      font-weight: 600;
      padding: 15px;
    }
    .table tbody td {
      padding: 12px 15px;
      border-bottom: 1px solid #e9ecef;
    }
    .table tbody tr:hover {
      background-color: #f8f9fa;
    }
    .page-title {
      color: white;
      font-weight: 700;
      text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
      margin-bottom: 2rem;
    }
    .admin-info {
      color: white;
      text-align: right;
    }
  </style>
</head>
<body>
<div class="container-fluid py-5">
  <div class="container">
    <div class="d-flex flex-column justify-content-center align-items-center mb-2">
      <h1 class="text-center page-title mb-3">
        <i class="fas fa-graduation-cap me-3"></i>
        Student Registration System
      </h1>
      <div class="admin-info">
        <?php if (isset($_SESSION['admin'])): ?>
          <div class="mb-2 ">
            <i class="fas fa-user-shield me-2 "></i>Admin Mode
          </div>
          <a href="logout.php" class="btn btn-outline-light btn-sm">
            <i class="fas fa-sign-out-alt me-1"></i> Logout
          </a>
        <?php else: ?>
          <a href="admin/admin_login.php" class="btn btn-outline-light btn-sm">
            <i class="fas fa-sign-in-alt me-1"></i> Admin Login
          </a>
        <?php endif; ?>
      </div>
    </div>

    <div class="row justify-content-center mb-5">
      <div class="col-lg-8">
        <div class="card main-card shadow-lg">
          <div class="card-header text-center py-4">
            <h4 class="mb-0">
              <i class="fas fa-user-plus me-2"></i>
              Register New Student
            </h4>
          </div>
          <div class="card-body p-4">
            <form id="registerForm">
              <div class="row">
                <div class="col-md-6 mb-4">
                  <label class="form-label"><i class="fas fa-user text-primary me-2"></i>Full Name</label>
                  <input type="text" name="name" class="form-control" placeholder="Enter full name">
                  <small class="error-msg"></small>
                </div>
                <div class="col-md-6 mb-4">
                  <label class="form-label"><i class="fas fa-envelope text-primary me-2"></i>Email Address</label>
                  <input type="text" name="email" class="form-control" placeholder="Enter email address">
                  <small class="error-msg"></small>
                </div>
                <div class="col-md-6 mb-4">
                  <label class="form-label"><i class="fas fa-book text-primary me-2"></i>Course</label>
                  <select name="course" class="form-select">
                    <option value="Select">-- Select Course --</option>
                    <option value="Science">Science</option>
                    <option value="Commerce">Commerce</option>
                    <option value="Arts">Arts</option>
                  </select>
                  <small class="error-msg"></small>
                </div>
                <div class="col-md-6 mb-4">
                  <label class="form-label"><i class="fas fa-phone text-primary me-2"></i>Phone Number</label>
                  <input type="text" name="number" class="form-control" placeholder="Enter phone number">
                  <small class="error-msg"></small>
                </div>
              </div>
              <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary btn-lg px-5">
                  <i class="fas fa-save me-2"></i> Register Student
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <h3 class="text-center text-white mb-4">
      <i class="fas fa-users me-2"></i> Registered Students
    </h3>
    <div class="table-responsive">
      <table class="table mb-0">
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Course</th>
            <th>Number</th>
          </tr>
        </thead>
        <tbody id="studentTable">
          <?php if (empty($students)): ?>
            <tr><td colspan="5" class="text-center py-4 text-muted">No students registered yet.</td></tr>
          <?php else: ?>
            <?php foreach ($students as $s): ?>
              <tr>
                <td><?= htmlspecialchars($s['id']) ?></td>
                <td><?= htmlspecialchars($s['name']) ?></td>
                <td><?= htmlspecialchars($s['email']) ?></td>
                <td><?= htmlspecialchars($s['course']) ?></td>
                <td><?= htmlspecialchars($s['number']) ?></td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$('#registerForm').on('submit', function(e) {
  e.preventDefault();
  $('.error-msg').text('');
  $('.alert').remove();

  $.post('user/register_student.php', $(this).serialize(), function(res) {
    if (res.success) {
      $('#registerForm')[0].reset();
      $('body').prepend(`<div class="alert alert-success text-center">
        <i class="fas fa-check-circle me-2"></i>${res.message}</div>`);

      // ✅ Append new row with returned data
      $('#studentTable').append(`<tr>
        <td>${res.data.id}</td>
        <td>${res.data.name}</td>
        <td>${res.data.email}</td>
        <td>${res.data.course}</td>
        <td>${res.data.number}</td>
      </tr>`);

      // ✅ Remove "No students" row if it exists
      $('#studentTable tr').each(function(){
        if ($(this).text().includes('No students')) {
          $(this).remove();
        }
      });

    } else {
      $.each(res.errors, function(k, v) {
        $('[name="'+k+'"]').next('.error-msg').text(v);
      });
    }
    setTimeout(() => { $('.alert').fadeOut(500, function(){ $(this).remove(); }); }, 3000);
  }, 'json');
});
</script>
</body>
</html>
