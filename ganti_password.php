<?php
require 'koneksi.php'; // file koneksi database

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$msg = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $current = $_POST['current_password'];
    $new = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];

    // Ambil password lama dari database
    $sql = "SELECT password FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($hashed_password);
    $stmt->fetch();
    $stmt->close();

    // Verifikasi password lama
    if (!password_verify($current, $hashed_password)) {
        $msg = "Password lama salah!";
    } elseif ($new !== $confirm) {
        $msg = "Konfirmasi password tidak cocok!";
    } else {
        // Update password baru
        $new_hashed = password_hash($new, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET password = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $new_hashed, $user_id);
        $stmt->execute();
        $stmt->close();
        $msg = "Password berhasil diubah!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Ubah Password</title>
    <style>
        body { font-family: sans-serif; background: #1e1e2f; color: #eee; padding: 20px; }
        form { max-width: 400px; margin: auto; }
        input { width: 100%; padding: 8px; margin: 8px 0; background: #2e2e3e; color: #eee; border: none; border-radius: 4px; }
        button { background: #4CAF50; color: white; padding: 10px; border: none; width: 100%; border-radius: 4px; cursor: pointer; }
        .msg { margin-top: 10px; color: yellow; }
    </style>
</head>
<body>

<h2>Ubah Password</h2>
<form method="post">
    <label>Password Lama</label>
    <input type="password" name="current_password" required>
    
    <label>Password Baru</label>
    <input type="password" name="new_password" required>
    
    <label>Konfirmasi Password Baru</label>
    <input type="password" name="confirm_password" required>
    
    <button type="submit">Ubah Password</button>
</form>
<div class="msg"><?= htmlspecialchars($msg) ?></div>

</body>
</html>
