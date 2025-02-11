<?php
include 'koneksi.php';

if (isset($_POST['register'])) {
    $username = mysqli_real_escape_string($conn_todolist, $_POST['username']);
    $email = mysqli_real_escape_string($conn_todolist, $_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        echo "Password dan konfirmasi password tidak cocok.";
        exit;
    }

    // 🔍 **Cek apakah email sudah terdaftar**
    $check_query = "SELECT * FROM users WHERE email='$email'";
    $check_result = mysqli_query($conn_todolist, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        echo "Email sudah terdaftar! Gunakan email lain.";
        exit;
    }

    // **Hash password sebelum disimpan**
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // **Simpan user baru**
    $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";
    if (mysqli_query($conn_todolist, $query)) {
        $user_id = mysqli_insert_id($conn_todolist);

        // **Catat di history bahwa user melakukan registrasi**
        $history_query = "INSERT INTO history (user_id, action) VALUES ('$user_id', 'register')";
        mysqli_query($conn_todolist, $history_query);

        header("Location: login2.php");
        exit;
    } else {
        echo "Registrasi gagal. Silakan coba lagi.";
    }
}
?>

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
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
