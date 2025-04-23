<?php
include 'koneksi.php';

// Cek session user
if (!isset($_SESSION['user_id'])) {
    header("Location: login2.php");
    exit;
}
$user_id = $_SESSION['user_id'];

// Ambil data history task (yang sudah selesai)
$q_history = "SELECT * FROM tasks WHERE user_id='$user_id' AND status='Selesai' ORDER BY updated_at DESC";
$run_q_history = mysqli_query($conn_todolist, $q_history);

// Ambil nama user
$q_user = "SELECT username FROM users WHERE id='$user_id'";
$run_q_user = mysqli_query($conn_todolist, $q_user);
$user = mysqli_fetch_assoc($run_q_user);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History Task</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        body {
          background-image: url("LoginRegister.jpg"),
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
<body class="bg-light">
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

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <h3 class="text-center text-white">Dashboard</h3>
    <a href="home.php" class="<?= $current_page == 'home.php' ? 'active' : '' ?>">🏠 Home</a>
    <a href="tasks.php" class="<?= $current_page == 'tasks.php' ? 'active' : '' ?>">📝 Tasks</a>
    
    
        <a href="tambah_tasks.php">+ Tambah Task</a>
       <a href="history.php">🕓 Riwayat Tugas</a>
   
    <a href="profile.php" class="<?= $current_page == 'profile.php' ? 'active' : '' ?>">👤 Profile</a>
</div>


<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card bg-white p-4 shadow">
                <h2 class="text-center mb-4 text-dark"><i class="fas fa-history"></i> History Penyelesaian Task</h2>
                

                <!-- Daftar History -->
                <?php if (mysqli_num_rows($run_q_history) > 0): ?>
                    <?php while ($task = mysqli_fetch_assoc($run_q_history)): ?>
                        <div class="card mb-3 p-3 history-card">
                            <div class="d-flex justify-content-between align-items-center">
                                <h4 class="text-dark mb-0"><?= htmlspecialchars($task['nama_tugas']) ?></h4>
                                <span class="badge bg-success">
                                    <i class="fas fa-check"></i> Selesai: <?= date('d M Y H:i', strtotime($task['updated_at'])) ?>
                                </span>
                            </div>
                            <div class="task-details mt-3 text-secondary">
                                <p><i class="fas fa-align-left"></i> <?= htmlspecialchars($task['deskripsi_tugas']) ?></p>
                                <div class="row">
                                    <div class="col-md-4">
                                        <p><i class="fas fa-calendar-plus"></i> Mulai: <?= date('d M Y', strtotime($task['created_at'])) ?></p>
                                    </div>
                                    <div class="col-md-4">
                                        <p><i class="fas fa-calendar-times"></i> Deadline: <?= date('d M Y', strtotime($task['deadline'])) ?></p>
                                    </div>
                                    <div class="col-md-4">
                                        <p><i class="fas fa-bolt"></i> Prioritas: <?= htmlspecialchars($task['prioritas']) ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="alert alert-info text-center">
                        <i class="fas fa-info-circle fa-2x mb-3"></i>
                        <h4 class="text-dark">Belum ada history task yang selesai</h4>
                        <p class="text-dark">Silakan selesaikan task terlebih dahulu untuk melihat history</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Fungsi untuk jam
    function updateClock() {
        let now = new Date();
        let hours = now.getHours().toString().padStart(2, '0');
        let minutes = now.getMinutes().toString().padStart(2, '0');
        let seconds = now.getSeconds().toString().padStart(2, '0');
        document.getElementById("clock").innerHTML = `<i class="fas fa-clock"></i> ${hours}:${minutes}:${seconds}`;
    }
    setInterval(updateClock, 1000);
    updateClock();

    // Fungsi untuk sidebar
    function toggleSidebar() {
        document.getElementById("sidebar").classList.toggle("active");
    }

    // Fungsi konfirmasi logout
    function confirmLogout() {
        if (confirm('Apakah kamu yakin ingin logout?')) {
            window.location.href = 'logout.php';
        }
    }
</script>
</body>
</html>