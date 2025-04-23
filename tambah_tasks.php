<?php
include 'koneksi.php';

// Cek session user di awal
if (!isset($_SESSION['user_id'])) {
    header("Location: login2.php");
    exit;
}

$user_id = $_SESSION['user_id']; // Definisikan $user_id di awal

// Proses form jika metode POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_task = trim($_POST['nama_task']);
    $deskripsi = trim($_POST['deskripsi_tugas']);
    $deadline = $_POST['deadline'];
    $tanggal_mulai = $_POST['tanggal_mulai'];
    $prioritas = $_POST['prioritas'];

    // Validasi input
    $errors = [];
    
    if (empty($nama_task)) {
        $errors[] = "Nama task harus diisi";
    }
    
    if (empty($deskripsi)) {
        $errors[] = "Deskripsi harus diisi";
    }

    if (empty($tanggal_mulai)) {
        $errors[] = "Tanggal mulai harus diisi";
    }
    
    if (empty($deadline)) {
        $errors[] = "Deadline harus diisi";
    }
    
    if (empty($prioritas)) {
        $errors[] = "Prioritas harus dipilih";
    }

    if (!empty($errors)) {
        $_SESSION['error_messages'] = $errors;
        header("Location: tambah_tasks.php");
        exit;
    }

    // Hindari SQL Injection
    $nama_task = mysqli_real_escape_string($conn_todolist, $nama_task);
    $deadline = mysqli_real_escape_string($conn_todolist, $deadline);
    $prioritas = mysqli_real_escape_string($conn_todolist, $prioritas);
    $deskripsi = mysqli_real_escape_string($conn_todolist, $deskripsi);

    // Cek apakah user_id valid
    $q_check_user = "SELECT id FROM users WHERE id = '$user_id'";
    $result = mysqli_query($conn_todolist, $q_check_user);
    
    if (!$result || mysqli_num_rows($result) == 0) {
        $_SESSION['error_messages'] = ["User ID tidak ditemukan di database"];
        header("Location: tambah_tasks.php");
        exit;
    }

    // Masukkan task ke database
    $query = "INSERT INTO tasks (user_id, nama_tugas, deskripsi_tugas,tanggal_mulai, deadline, prioritas) 
              VALUES ('$user_id', '$nama_task', '$deskripsi','$tanggal_mulai', '$deadline', '$prioritas')";
    
    if (mysqli_query($conn_todolist, $query)) {  
        $_SESSION['success_message'] = "Task berhasil ditambahkan!";
        header("Location: tasks.php");
        exit;
    } else {
        $_SESSION['error_messages'] = ["Gagal menambahkan task: " . mysqli_error($conn_todolist)];
        header("Location: tambah_tasks.php");
        exit;
    }
}

// Ambil data user untuk ditampilkan di form
$q_user = "SELECT username FROM users WHERE id='$user_id'";
$run_q_user = mysqli_query($conn_todolist, $q_user);

if ($run_q_user && mysqli_num_rows($run_q_user) > 0) {
    $user = mysqli_fetch_assoc($run_q_user);
    $username = htmlspecialchars($user['username']);
} else {
    $_SESSION['error_messages'] = ["User tidak ditemukan"];
    header("Location: login2.php");
    exit;
}

// Ambil jumlah task yang belum selesai
$q_pending = "SELECT COUNT(*) as total FROM tasks WHERE user_id='$user_id' AND status='Belum Selesai'";
$run_q_pending = mysqli_query($conn_todolist, $q_pending);
$pending_tasks = $run_q_pending ? mysqli_fetch_assoc($run_q_pending)['total'] : 0;
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Task</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
<body class="bg-light text-white">

<!-- Navbar -->
<div class="navbar">
    <span class="toggle-sidebar" onclick="toggleSidebar()">☰</span>
    <div class="user-info">
    <span>👋 Welcome, <?= htmlspecialchars($user['username']) ?>!</span>
        <span id="clock">🕒 00:00:00</span>
        <span>🔔 <?= $pending_tasks ?> Task Pending</span>
        <a href="logout.php" class="btn btn-sm btn-danger">Logout</a>
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

<!-- Form Tambah Task -->
<div class="container mt-4" style="margin-left: 220px;">
    <h2 class="mb-4">Tambah Task Baru</h2>
    <form action="tambah_tasks.php" method="POST" class="bg-secondary p-4 rounded shadow">
        <div class="mb-3">
            <label for="nama_task" class="form-label">Nama Task</label>
            <input type="text" name="nama_task" id="nama_task" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="deskripsi_task" class="form-label">Deskripsi Task</label>
            <textarea name="deskripsi_tugas" id="deskripsi_tugas" class="form-control" rows="3" required></textarea>
        </div>
        <div class="mb-3">
            <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
            <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control" required min="<?= date('Y-m-d') ?>">
        </div>
        <div class="mb-3">
            <label for="deadline" class="form-label">Deadline</label>
            <input type="date" name="deadline" id="deadline" class="form-control" required min="<?= date('Y-m-d') ?>">
        </div>
        <div class="mb-3">
            <label for="prioritas" class="form-label">Prioritas</label>
            <select name="prioritas" id="prioritas" class="form-control" required>
                <option value="" disabled selected>Pilih Prioritas</option>
                <option value="Tinggi">Tinggi</option>
                <option value="Sedang">Sedang</option>
                <option value="Rendah">Rendah</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Simpan Task</button>
        <a href="tasks.php" class="btn btn-outline-light ms-2">Batal</a>
    </form>
</div>

<!-- Script -->
<script>
    function toggleSidebar() {
        const sidebar = document.getElementById("sidebar");
        sidebar.classList.toggle("active");
    }

    function updateClock() {
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        document.getElementById("clock").textContent = `🕒 ${hours}:${minutes}:${seconds}`;
    }
    setInterval(updateClock, 1000);
    updateClock();
</script>

</body>
</html>
