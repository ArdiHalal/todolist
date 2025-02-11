<?php
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $nama_list = mysqli_real_escape_string($conn_todolist, $_POST['nama_list']);

    // Cek apakah user_id ada di tabel users
    $q_check_user = "SELECT id FROM users WHERE id = '$user_id'";
    $result = mysqli_query($conn_todolist, $q_check_user);

    if (mysqli_num_rows($result) == 0) {
        die("Error: User ID tidak ditemukan di database.");
    }

    $query = "INSERT INTO lists (user_id, nama_list) VALUES ('$user_id', '$nama_list')";
    mysqli_query($conn_todolist, $query);
    header("Location: index2.php");
    exit;
}
?>