<?php
include 'koneksi.php';

// Cek session user
if (!isset($_SESSION['user_id'])) {
    header("Location: login2.php");
    exit;
}
$user_id = $_SESSION['user_id'];

// Ambil statistik task
$q_pending_tasks = "SELECT COUNT(*) as total FROM tasks WHERE user_id='$user_id' AND status='Belum Selesai'";
$q_completed_tasks = "SELECT COUNT(*) as total FROM tasks WHERE user_id='$user_id' AND status='Selesai'";

$run_q_pending = mysqli_query($conn_todolist, $q_pending_tasks);
$run_q_completed = mysqli_query($conn_todolist, $q_completed_tasks);

$pending_tasks = mysqli_fetch_assoc($run_q_pending)['total'];
$completed_tasks = mysqli_fetch_assoc($run_q_completed)['total'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - To-Do List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body class="bg-light text-white">
<!-- Navbar -->
<div class="navbar">
    <span class="toggle-sidebar" onclick="toggleSidebar()">☰</span>
    <div class="user-info">
        <span>👋 Welcome, User!</span>
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

<!-- Konten Utama -->
<div class="container mt-5">
    <h2>🏠 Home</h2>
    <div class="row">
        <!-- Card Task Pending -->
        <div class="col-md-6 mb-3">
            <div class="card bg-secondary text-white p-3">
                <h5>📊 Task Pending</h5>
                <p class="text-white">Total: <?= $pending_tasks ?></p>
            </div>
        </div>
        <!-- Card Task Completed -->
        <div class="col-md-6 mb-3">
            <div class="card bg-secondary text-white p-3">
                <h5>📊 Task Completed</h5>
                <p class="text-white">Total: <?= $completed_tasks ?></p>
            </div>
        </div>
    </div>
</div>

<script>
    // Fungsi untuk menampilkan/menyembunyikan sidebar
    function toggleSidebar() {
        const sidebar = document.getElementById("sidebar");
        const container = document.querySelector(".container");

        sidebar.classList.toggle("active");
        container.classList.toggle("sidebar-active");
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