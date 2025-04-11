<?php
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validasi input
    if (empty($_POST['email']) || empty($_POST['password'])) {
        $_SESSION['error'] = "Email dan password harus diisi.";
        header("Location: login2.php");
        exit;
    }

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Gunakan prepared statement untuk keamanan
    $query = "SELECT id, email, password FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn_todolist, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result && $user = mysqli_fetch_assoc($result)) {
            // Verifikasi password
            if (password_verify($password, $user['password'])) {
                // Set session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['email'] = $user['email'];

                // Simpan riwayat login ke history
                $history_query = "INSERT INTO history (user_id, action) VALUES (?, 'login')";
                $stmt_history = mysqli_prepare($conn_todolist, $history_query);

                if ($stmt_history) {
                    mysqli_stmt_bind_param($stmt_history, "i", $user['id']);
                    mysqli_stmt_execute($stmt_history);
                    mysqli_stmt_close($stmt_history);
                }

                // Redirect ke halaman utama
                header("Location: index2.php");
                exit;
            } else {
                $_SESSION['error'] = "Login gagal. Periksa email atau password Anda.";
                header("Location: login2.php");
                exit;
            }
        } else {
            $_SESSION['error'] = "Login gagal. Akun tidak ditemukan.";
            header("Location: login2.php");
            exit;
        }
        mysqli_stmt_close($stmt);
    } else {
        die("Query Error: " . mysqli_error($conn_todolist));
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
    <link rel="stylesheet" href="styles2.css">
</head>
<body>
    <div class="global-container">
        <div class="login-form">
            <h2 class="text-center card-title">L o g i n</h2>
            <?php
            // Tampilkan pesan error jika ada
            if (isset($_SESSION['error'])) {
                echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
                unset($_SESSION['error']); // Hapus pesan error setelah ditampilkan
            }
            ?>
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