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

// Ambil nama user
$q_user = "SELECT username FROM users WHERE id='$user_id'";
$run_q_user = mysqli_query($conn_todolist, $q_user);
$user = mysqli_fetch_assoc($run_q_user);

// Ambil jumlah task yang belum selesai
$q_pending = "SELECT COUNT(*) as total FROM tasks WHERE user_id='$user_id' AND status='Belum Selesai'";
$run_q_pending = mysqli_query($conn_todolist, $q_pending);
$pending_tasks = mysqli_fetch_assoc($run_q_pending)['total'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - To-Do List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        body {
          background-image: url("Home.jpg"),
            radial-gradient(circle at 10% 20%, rgba(181, 234, 215, 0.1) 0%, transparent 20%),
            radial-gradient(circle at 90% 80%, rgba(230, 230, 250, 0.1) 0%, transparent 20%);
          background-size: cover; /* Membuat gambar background full layar */
          background-repeat: no-repeat; /* Mencegah pengulangan gambar */
          background-position: center; /* Memposisikan gambar di tengah */
          background-attachment: fixed;
          color: var(--text-light);
          font-family: 'Poppins', sans-serif;
        }

    </style>
</head>
<body class="bg-light text-white">
<!-- Navbar -->
<div class="navbar">
    <span class="toggle-sidebar" onclick="toggleSidebar()">☰</span>
    <div class="user-info">
        <span>👋 Welcome, <?= htmlspecialchars($user['username']) ?>!</span>
        <span id="clock">🕒 00:00:00</span>
        <span>🔔 <?= $pending_tasks ?> Task Pending</span>
        <a href="javascript:void(0);" class="btn btn-sm btn-danger" onclick="confirmLogout()">Logout</a>
    </div>
</div>

<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <h3 class="text-center text-white">Dashboard</h3>
    <a href="home.php" class="<?= $current_page == 'home.php' ? 'active' : '' ?>">🏠 Home</a>
    <a href="tasks.php" class="<?= $current_page == 'tasks.php' ? 'active' : '' ?>">📝 Tasks</a>
    
    <?php if ($current_page == 'tasks.php'): ?>
        <a href="#addTaskForm" onclick="showAddTaskForm()">+ Tambah Task</a>
    <?php endif; ?>

    <?php if ($current_page == 'tasks.php'): ?>
        <a href="history.php" onclick="showAddTaskForm()">🕓 Riwayat Tugas</a>
    <?php endif; ?>

    <a href="profile.php" class="<?= $current_page == 'profile.php' ? 'active' : '' ?>">👤 Profile</a>
</div>


<!-- Konten Utama -->
<div class="container mt-5">
    <h2 class="text-white">🏠 Home</h2>
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    function confirmLogout() {
        if (confirm('Apakah kamu yakin ingin logout?')) {
            window.location.href = 'logout.php';
        }
    }
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