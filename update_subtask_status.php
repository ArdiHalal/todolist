<?php
include 'koneksi.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subtaskId = intval($_POST['id']);
    $newStatus = $_POST['status'];

    $query = "UPDATE sub_tasks SET status='$newStatus' WHERE id='$subtaskId'";
    if (mysqli_query($conn_todolist, $query)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => mysqli_error($conn_todolist)]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>