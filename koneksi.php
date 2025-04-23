<?php
session_start();
$host = "localhost";
$user = "ardz9358_arditaUjikomInternal";
$pass = "DitttStayHalal321*";
$db1 = "ardz9358_ardita_ujikomInternal";

// Koneksi ke database to-do list
$conn_todolist = mysqli_connect($host, $user, $pass, $db1);
if (!$conn_todolist) {
    die("Koneksi ke database To-Do List gagal: " . mysqli_connect_error());
}
?>