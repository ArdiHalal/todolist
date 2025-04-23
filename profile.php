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
    <title>Profile - To-Do List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        body {
          background-image: url("Profile.jpg"),
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
<!-- Navbar dan Sidebar (sama seperti index.php) -->
<div class="navbar">
    <span class="toggle-sidebar" onclick="toggleSidebar()">☰</span>
    <div class="user-info">
        <span>👋 Welcome, <?= htmlspecialchars($user['username']) ?>!</span>
        <span id="clock">🕒 00:00:00</span>
        <span>🔔 <?= $pending_tasks ?> Task Pending</span>
        <a href="javascript:void(0);" class="btn btn-sm btn-danger" onclick="confirmLogout()">Logout</a>
    </div>
</div>

<!-- Sidebar -->
<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <h3 class="text-center text-white">Dashboard</h3>
    <a href="home.php" class="<?= $current_page == 'home.php' ? 'active' : '' ?>">🏠 Home</a>
    <a href="tasks.php" class="<?= $current_page == 'tasks.php' ? 'active' : '' ?>">📝 Tasks</a>
    
    <?php if ($current_page == 'tasks.php'): ?>
        <a href="#addTaskForm" onclick="showAddTaskForm()">+ Tambah Tugas</a>
    <?php endif; ?>

    <?php if ($current_page == 'tasks.php'): ?>
        <a href="history.php" onclick="showAddTaskForm()">🕓 Riwayat Tugas</a>
    <?php endif; ?>

    <a href="profile.php" class="<?= $current_page == 'profile.php' ? 'active' : '' ?>">👤 Profile</a>
</div>


<div class="container mt-5">
    <h2 class="text-white">👤 Profile</h2>
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
    function confirmLogout() {
        if (confirm('Apakah kamu yakin ingin logout?')) {
            window.location.href = 'logout.php';
        }
    }

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