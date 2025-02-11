<?php
include 'koneksi.php';

if (isset($_POST['add'])) {
    $list_id = $_POST['list_id'];
    $task = $_POST['task'];
    $prioritas = $_POST['prioritas']; // Ambil data prioritas dari form

    // Simpan ke database
    $query = "INSERT INTO tasks (list_id, nama_tugas, status, prioritas) VALUES ('$list_id', '$task', 'Belum', '$prioritas')";
    mysqli_query($conn_todolist, $query);

    // Redirect balik ke halaman utama
    header("Location: index2.php");
    exit;
}
?>
