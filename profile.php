<?php
include 'koneksi.php';

// Cek session user
if (!isset($_SESSION['user_id'])) {
    header("Location: login2.php");
    exit;
}
$user_id = $_SESSION['user_id'];

// Ambil data user
$q_user = "SELECT username,email FROM users WHERE id='$user_id'";
$run_q_user = mysqli_query($conn_todolist, $q_user);
$user = mysqli_fetch_assoc($run_q_user);

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - To-Do List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body class="bg-light text-white">
<!-- Navbar dan Sidebar (sama seperti index.php) -->
<div class="navbar">
    <span class="toggle-sidebar" onclick="toggleSidebar()">☰</span>
    <div class="user-info">
        <span>👋 Welcome, <?= htmlspecialchars($user['username']) ?>!</span>
        <span id="clock">🕒 00:00:00</span>
        <a href="logout.php" class="btn btn-sm btn-danger">Logout</a>
    </div>
</div>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <h3 class="text-center text-white">Dashboard</h3>
    <a href="home.php">🏠 Home</a>
    <a href="tasks.php">📝 Tasks</a>
    <a href="profile.php">👤 Profile</a>
</div>

<div class="container mt-5">
    <h2>👤 Profile</h2>
    <div class="row">
        <div class="col-md-6">
            <div class="card bg-secondary text-white p-3">
                <h5>User Information</h5>
                <p class= "bg-secondary text-white">Username: <?= htmlspecialchars($user['username']) ?></p>
                <p class= "bg-secondary text-white">Email: <?= htmlspecialchars($user['email']) ?></p>
                <a href="ganti_password.php" class="btn btn-warning">Change Password</a>
            </div>
        </div>
    </div>
</div>
<script>
     // Toggle Sidebar
     function toggleSidebar() {
        document.getElementById("sidebar").classList.toggle("active");
    }

    // Update Waktu Real-Time
    function updateClock() {
        let now = new Date();
        let hours = now.getHours().toString().padStart(2, '0');
        let minutes = now.getMinutes().toString().padStart(2, '0');
        let seconds = now.getSeconds().toString().padStart(2, '0');
        document.getElementById("clock").textContent = `🕒 ${hours}:${minutes}:${seconds}`;
    }
    setInterval(updateClock, 1000);
    updateClock();
</script>
</body>
</html>