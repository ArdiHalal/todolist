<?php
include 'koneksi.php';

// Validasi session
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// Validasi input
if (!isset($_POST['id']) || !isset($_POST['status'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit;
}

// Sanitize input
$task_id = (int)$_POST['id'];
$new_status = $_POST['status'];

// Validasi nilai status
$allowed_statuses = ['Belum Selesai', 'Selesai'];
if (!in_array($new_status, $allowed_statuses)) {
    echo json_encode(['success' => false, 'message' => 'Status tidak valid']);
    exit;
}

// Update status task langsung di tabel tasks
$stmt_update = mysqli_prepare($conn_todolist, 
    "UPDATE tasks SET status=? WHERE id=? AND user_id=?"
);
mysqli_stmt_bind_param($stmt_update, "sii", $new_status, $task_id, $_SESSION['user_id']);

if (mysqli_stmt_execute($stmt_update)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Gagal mengupdate status']);
}
?>
