<?php
include 'koneksi.php';

// Mulai transaksi
mysqli_begin_transaction($conn_todolist);

try {
    // Simpan riwayat penghapusan semua task
    $history_query = "INSERT INTO history (user_id, action) VALUES (?, 'delete_all_tasks')";
    $stmt = mysqli_prepare($conn_todolist, $history_query);
    mysqli_stmt_bind_param($stmt, "i", $_SESSION['user_id']);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // Hapus semua task (sub-tasks juga ikut kehapus kalau ada foreign key ON DELETE CASCADE)
    $delete_tasks = "DELETE FROM tasks";
    mysqli_query($conn_todolist, $delete_tasks);

    // Commit transaksi kalau semua berhasil
    mysqli_commit($conn_todolist);

    echo "<script>alert('Semua task berhasil dihapus!'); window.location='index2.php';</script>";
} catch (Exception $e) {
    // Rollback kalau ada error
    mysqli_rollback($conn_todolist);
    echo "<script>alert('Gagal menghapus semua task!'); window.location='index2.php';</script>";
}
?>
