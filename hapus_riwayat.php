<?php
include 'koneksi.php';

if (isset($_POST['user_id'])) {
    $user_id = (int)$_POST['user_id']; // selalu casting untuk keamanan

    // Hapus semua task yang statusnya "Selesai" milik user ini
    $delete_query = "DELETE FROM tasks WHERE user_id = $user_id AND status = 'Selesai'";
    
    if (mysqli_query($conn_todolist, $delete_query)) {
        echo "Tugas yang selesai berhasil dihapus!";
    } else {
        echo "Terjadi kesalahan saat menghapus tugas.";
    }
}
?>
