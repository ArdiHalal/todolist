<?php
include 'koneksi.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $task_id = $_GET['id'];

    // Mulai transaksi
    mysqli_begin_transaction($conn_todolist);

    try {
        // Simpan riwayat penghapusan task sebelum dihapus
        $history_query = "INSERT INTO history (user_id, action) VALUES (?, 'delete_task')";
        $stmt = mysqli_prepare($conn_todolist, $history_query);
        mysqli_stmt_bind_param($stmt, "i", $_SESSION['user_id']); 
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        // Hapus task berdasarkan ID
        $delete_task = "DELETE FROM tasks WHERE id = ?";
        $stmt = mysqli_prepare($conn_todolist, $delete_task);
        mysqli_stmt_bind_param($stmt, "i", $task_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        // Commit transaksi kalau semua berhasil
        mysqli_commit($conn_todolist);

        echo "<script>alert('Task berhasil dihapus!'); window.location='index2.php';</script>";
    } catch (Exception $e) {
        // Rollback kalau ada error
        mysqli_rollback($conn_todolist);
        echo "<script>alert('Gagal menghapus task!'); window.location='index2.php';</script>";
    }
} else {
    echo "<script>alert('ID task tidak valid!'); window.location='index2.php';</script>";
}
?>
