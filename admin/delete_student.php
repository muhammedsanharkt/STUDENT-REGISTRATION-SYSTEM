<?php
session_start();
require_once '../user/student.php';

$response = ['success' => false];

if (isset($_SESSION['admin']) && isset($_POST['id'])) {
    $student = new Student();
    $id = intval($_POST['id']);

    if ($student->delete($id)) {
        $response['success'] = true;
    }
}

header('Content-Type: application/json');
echo json_encode($response);
exit;
