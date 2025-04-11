<?php
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $nama_task = trim($_POST['nama_task']);
    $deadline = $_POST['deadline'];
    $prioritas = $_POST['prioritas'];

    // Cek apakah input kosong
    if (empty($nama_task) || empty($deadline) || empty($prioritas) ) {
        die("Error: Semua kolom harus diisi.");
    }

    // Hindari SQL Injection
    $nama_task = mysqli_real_escape_string($conn_todolist, $nama_task);
    $deadline = mysqli_real_escape_string($conn_todolist, $deadline);
    $prioritas = mysqli_real_escape_string($conn_todolist, $prioritas);

    // Cek apakah user_id ada di tabel users
    $q_check_user = "SELECT id FROM users WHERE id = '$user_id'";
    $result = mysqli_query($conn_todolist, $q_check_user);

    if (mysqli_num_rows($result) == 0) {
        die("Error: User ID tidak ditemukan di database.");
    }

    // Tambahkan task ke database
    $query = "INSERT INTO tasks (user_id, nama_tugas, deadline, prioritas) 
              VALUES ('$user_id', '$nama_task', '$deadline', '$prioritas')";
    
    if (mysqli_query($conn_todolist, $query)) {
        // Catat di history bahwa user menambahkan task
        $history_query = "INSERT INTO history (user_id, action) VALUES ('$user_id', 'add_task')";
        mysqli_query($conn_todolist, $history_query);

        header("Location: tasks.php");
        exit;
    } else {
        die("Error: Gagal menambahkan task.");
    }
}
?>
