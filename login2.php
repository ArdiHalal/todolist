<?php
include 'koneksi.php';

// Pastikan form dikirim dengan metode POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['email']) || !isset($_POST['password'])) {
        die("Error: Data login tidak lengkap.");
    }

    $email = mysqli_real_escape_string($conn_todolist, $_POST['email']);
    $password = $_POST['password'];

    // Cek apakah email ada di database
    $query = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn_todolist, $query);

    if (!$result) {
        die("Error Query: " . mysqli_error($conn_todolist));
    }

    $user = mysqli_fetch_assoc($result);

    // Verifikasi password
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];

        // Simpan riwayat login ke history
        $history_query = "INSERT INTO history (user_id, action) VALUES ('{$user['id']}', 'login')";
        mysqli_query($conn_todolist, $history_query);

        header("Location: index2.php");
        exit;
    } else {
        echo "Login gagal. Periksa email atau password Anda.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Form Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="global-container">
        <div class="login-form">
            <h2 class="text-center card-title">L o g i n</h2>
            <form action="login2.php" method="POST">
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" required>
                </div>
                <button type="submit" name="login" class="btn btn-custom">Login</button>
            </form>
            <div class="login-link">
                Don't have an account? <a href="register1.php">Register</a>
            </div>
        </div>
    </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
