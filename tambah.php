<?php
include 'koneksi.php';

if (isset($_POST['add']) && isset($_SESSION['user_id'])) {
    $task = $_POST['task'];
    $user_id = $_SESSION['user_id'];

    $q_insert = "INSERT INTO tasks (nama_tugas, status, user_id) VALUES ('$task', 'Belum selesai', '$user_id')";
    mysqli_query($conn_todolist, $q_insert);

    header("Location: index2.php");
}
?>