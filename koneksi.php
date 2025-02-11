<?php
session_start();
$host = "localhost";
$user = "root";
$pass = "";
$db1 = "todolist2";

// Koneksi ke database to-do list
$conn_todolist = mysqli_connect($host, $user, $pass, $db1);
if (!$conn_todolist) {
    die("Koneksi ke database To-Do List gagal: " . mysqli_connect_error());
}


?>