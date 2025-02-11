<?php
include 'koneksi.php';

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Simpan riwayat logout
    $history_query = "INSERT INTO history (user_id, action) VALUES ('$user_id', 'logout')";
    mysqli_query($conn_todolist, $history_query);

    // Hapus sesi
    session_destroy();
}

header("Location: login2.php");
exit;
?>
