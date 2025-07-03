<?php
session_start();
require_once 'student.php';

$student = new Student();
$response = ['success' => false, 'message' => '', 'errors' => []];

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$course = trim($_POST['course'] ?? '');
$number = trim($_POST['number'] ?? '');

$isValid = true;

// Validate name
if (empty($name)) {
  $response['errors']['name'] = "Name is required.";
  $isValid = false;
} elseif (!preg_match("/^[a-zA-Z\s]+$/", $name)) {
  $response['errors']['name'] = "Name must contain only letters and spaces.";
  $isValid = false;
}

// Validate email
if (empty($email)) {
  $response['errors']['email'] = "Email is required.";
  $isValid = false;
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  $response['errors']['email'] = "Invalid email format.";
  $isValid = false;
}

// Validate course
if (empty($course) || $course === "Select") {
  $response['errors']['course'] = "Please select a valid course.";
  $isValid = false;
}

// Validate number
if (empty($number)) {
  $response['errors']['number'] = "Phone number is required.";
  $isValid = false;
} elseif (!preg_match("/^[0-9]{1,10}$/", $number)) {
  $response['errors']['number'] = "Phone must be numbers only, max 10 digits.";
  $isValid = false;
}

if ($isValid) {
  $insertId = $student->register($name, $email, $course, $number);
  if ($insertId) {
    $response['success'] = true;
    $response['message'] = "Student registered successfully!";
    $response['data'] = [ // ✅ Make sure this is `data` to match your JS
      'id' => $insertId,
      'name' => $name,
      'email' => $email,
      'course' => $course,
      'number' => $number
    ];
  } else {
    $response['message'] = "Error: Email must be unique.";
  }
}

header('Content-Type: application/json');
echo json_encode($response);
exit;
