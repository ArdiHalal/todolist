<?php
include 'koneksi.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $subtaskId = intval($input['id']);
    $newStatus = $input['status'];

    if ($newStatus === 'Selesai') {
        $query = "UPDATE sub_tasks SET status='Selesai' WHERE id='$subtaskId'";
    } else {
        $query = "UPDATE sub_tasks SET status='$newStatus' WHERE id='$subtaskId'";
    }
    
    if (mysqli_query($conn_todolist, $query)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => mysqli_error($conn_todolist)]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>