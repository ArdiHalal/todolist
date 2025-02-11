<?php
include 'koneksi.php';

if (isset($_GET['id'])) {
    $list_id = mysqli_real_escape_string($conn_todolist, $_GET['id']);
    
    // Hapus semua tugas dalam list terlebih dahulu
    $delete_tasks = "DELETE FROM tasks WHERE list_id='$list_id'";
    mysqli_query($conn_todolist, $delete_tasks);
    
    // Hapus list itu sendiri
    $delete_list = "DELETE FROM lists WHERE id='$list_id'";
    if (mysqli_query($conn_todolist, $delete_list)) {
        echo "<script>alert('List berhasil dihapus!'); window.location='index2.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus list!'); window.location='index2.php';</script>";
    }
} else {
    echo "<script>alert('ID tidak ditemukan!'); window.location='index2.php';</script>";
}
?>
