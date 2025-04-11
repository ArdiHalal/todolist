<?php
include 'koneksi.php';

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Hapus semua sesi
    session_unset();
    session_destroy();
}
// Redirect ke halaman login
header("Location: login2.php");
exit;
?>