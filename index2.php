<?php
include 'koneksi.php';

// Cek session user
if (!isset($_SESSION['user_id'])) {
    header("Location: landing_page.php");
    exit;
}

// Redirect ke home.php
header("Location: home.php");
exit;
?>