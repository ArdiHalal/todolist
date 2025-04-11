<?php
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['username'], $_POST['email'], $_POST['password'], $_POST['confirm_password'])) {
        die("Error: Data tidak lengkap.");
    }

    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Cek apakah password dan konfirmasi cocok
    if ($password !== $confirm_password) {
        echo "<script>alert('Password dan konfirmasi password tidak cocok.'); window.location.href='register1.php';</script>";
        exit;
    }

    // Cek apakah email sudah terdaftar
    $check_query = "SELECT id FROM users WHERE email = ?";
    $stmt_check = mysqli_prepare($conn_todolist, $check_query);
    mysqli_stmt_bind_param($stmt_check, "s", $email);
    mysqli_stmt_execute($stmt_check);
    mysqli_stmt_store_result($stmt_check);

    if (mysqli_stmt_num_rows($stmt_check) > 0) {
        echo "<script>alert('Email sudah terdaftar! Gunakan email lain.'); window.location.href='register1.php';</script>";
        exit;
    }
    mysqli_stmt_close($stmt_check);

    // Hash password sebelum disimpan
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Simpan user baru ke database
    $query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn_todolist, $query);
    mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hashed_password);

    if (mysqli_stmt_execute($stmt)) {
        $user_id = mysqli_insert_id($conn_todolist);
        mysqli_stmt_close($stmt);

        // Simpan ke history bahwa user melakukan registrasi
        $history_query = "INSERT INTO history (user_id, action) VALUES (?, 'register')";
        $stmt_history = mysqli_prepare($conn_todolist, $history_query);
        mysqli_stmt_bind_param($stmt_history, "i", $user_id);
        mysqli_stmt_execute($stmt_history);
        mysqli_stmt_close($stmt_history);

        echo "<script>alert('Registrasi berhasil! Silakan login.'); window.location.href='login2.php';</script>";
        exit;
    } else {
        echo "<script>alert('Registrasi gagal. Silakan coba lagi.'); window.location.href='register1.php';</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles2.css">
</head>
<body>
    <div class="global-container">
        <div class="login-form">
            <h2 class="text-center card-title">R e g i s t e r</h2>
            <form action="register1.php" method="POST">
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" class="form-control" name="username" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" name="confirm_password" required>
                </div>
                <button type="submit" name="register" class="btn btn-custom">Register</button>
            </form>
            <div class="login-link">
                Already have an account? <a href="login2.php">Login</a>
            </div> 
        </div>
    </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
