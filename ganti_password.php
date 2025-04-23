<?php
require 'koneksi.php';

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
    $stmt = $conn_todolist->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($hashed_password);
    $stmt->fetch();
    $stmt->close();

    // Verifikasi password lama
    if (!password_verify($current, $hashed_password)) {
        $msg = "❌ Password lama salah!";
    } elseif ($new !== $confirm) {
        $msg = "❌ Konfirmasi password tidak cocok!";
    } else {
        // Hash password baru & update
        $new_hashed = password_hash($new, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET password = ? WHERE id = ?";
        $stmt = $conn_todolist->prepare($sql);
        $stmt->bind_param("si", $new_hashed, $user_id);
        $stmt->execute();
        $stmt->close();
        header("Location: profile.php");
exit;

    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Ubah Password</title>
    <style>
        body {
           background-image: url("Home.jpg"),
            radial-gradient(circle at 10% 20%, rgba(181, 234, 215, 0.1) 0%, transparent 20%),
            radial-gradient(circle at 90% 80%, rgba(230, 230, 250, 0.1) 0%, transparent 20%);
            color: #f1f1f1;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
        }

        form {
            background: #2e2e3e;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 400px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #ffffff;
        }

        label {
            display: block;
            margin-top: 15px;
            margin-bottom: 5px;
            font-weight: 600;
        }

        input {
            width: 100%;
            padding: 10px;
            background: #3b3b4f;
            color: #fff;
            border: 1px solid #555;
            border-radius: 8px;
            transition: 0.3s;
        }

        input:focus {
            outline: none;
            border-color: #4CAF50;
            background: #444;
        }

        button {
            background: #4CAF50;
            color: white;
            padding: 12px;
            border: none;
            width: 100%;
            border-radius: 8px;
            font-weight: bold;
            margin-top: 20px;
            transition: 0.3s;
        }

        button:hover {
            background: #45a049;
        }

        .msg {
            margin-top: 15px;
            text-align: center;
            color: #f1c40f;
            font-weight: bold;
        }
    </style>
</head>
<body>

<form method="post">
    <h2>Ubah Password</h2>

    <label>Password Lama</label>
    <input type="password" name="current_password" required>
    
    <label>Password Baru</label>
    <input type="password" name="new_password" required>
    
    <label>Konfirmasi Password Baru</label>
    <input type="password" name="confirm_password" required>
    
    <button type="submit">Ubah Password</button>
    <div class="msg"><?= htmlspecialchars($msg) ?></div>
</form>

</body>
</html>
