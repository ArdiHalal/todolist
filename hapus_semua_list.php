<?php
include 'koneksi.php';

// Hapus semua tugas terlebih dahulu
$delete_tasks = "DELETE FROM tasks";
mysqli_query($conn_todolist, $delete_tasks);

// Hapus semua list
$delete_lists = "DELETE FROM lists";
if (mysqli_query($conn_todolist, $delete_lists)) {
    echo "<script>alert('Semua list berhasil dihapus!'); window.location='index2.php';</script>";
} else {
    echo "<script>alert('Gagal menghapus semua list!'); window.location='index2.php';</script>";
}
?>
