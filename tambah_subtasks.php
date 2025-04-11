<?php
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
    $task_id = $_POST['task_id'] ?? '';
    $prioritas = $_POST['prioritas'] ?? 'Sedang'; // Default 'Sedang'
    $nama_subtask = trim($_POST['nama_subtask'] ?? '');

    // Validasi input
    if (empty($task_id) || empty($nama_subtask)) {
        die("Error: Task ID atau Nama Subtask tidak boleh kosong.");
    }

    // Cegah SQL Injection dengan Prepared Statement
    $query = "INSERT INTO sub_tasks (task_id, nama_subtask, prioritas) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn_todolist, $query);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "iss", $task_id, $nama_subtask, $prioritas);
        if (mysqli_stmt_execute($stmt)) {
            header("Location: tasks.php");
            exit;
        } else {
            die("Error: Gagal menambahkan subtask.");
        }
        mysqli_stmt_close($stmt);
    } else {
        die("Error: Query tidak valid.");
    }
} else {
    die("Error: Request tidak valid.");
}
?>
