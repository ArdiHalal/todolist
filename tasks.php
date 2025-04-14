<?php
include 'koneksi.php';

// Cek session user
if (!isset($_SESSION['user_id'])) {
    header("Location: login2.php");
    exit;
}
$user_id = $_SESSION['user_id'];

// Ambil filter dari URL (jika ada)
$filter_prioritas = isset($_GET['prioritas']) ? $_GET['prioritas'] : '';
$filter_deadline = isset($_GET['deadline']) ? $_GET['deadline'] : '';
$filter_status = isset($_GET['status']) ? $_GET['status'] : '';

// Modifikasi query untuk menambahkan keterangan deadline terlewat
$today = date('Y-m-d');
$q_tasks = "SELECT *, 
            CASE 
                WHEN deadline < '$today' AND status = 'Belum Selesai' THEN 'Terlewat'
                ELSE 'Tepat Waktu'
            END AS deadline_status
            FROM tasks WHERE user_id='$user_id'";

// Modifikasi query berdasarkan filter
if (!empty($filter_prioritas)) {
    $q_tasks .= " AND prioritas='$filter_prioritas'";
}
if (!empty($filter_status)) {
    $q_tasks .= " AND status='$filter_status'";
}
if (!empty($filter_deadline)) {
    switch ($filter_deadline) {
        case 'today':
            $q_tasks .= " AND deadline='$today'";
            break;
        case 'tomorrow':
            $tomorrow = date('Y-m-d', strtotime('+1 day'));
            $q_tasks .= " AND deadline='$tomorrow'";
            break;
        case 'this_week':
            $end_of_week = date('Y-m-d', strtotime('next Sunday'));
            $q_tasks .= " AND deadline BETWEEN '$today' AND '$end_of_week'";
            break;
        case 'next_week':
            $start_of_next_week = date('Y-m-d', strtotime('next Monday'));
            $end_of_next_week = date('Y-m-d', strtotime('next Sunday +1 week'));
            $q_tasks .= " AND deadline BETWEEN '$start_of_next_week' AND '$end_of_next_week'";
            break;
    }
}

$run_q_tasks = mysqli_query($conn_todolist, $q_tasks);

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
    <title>To-Do List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .border-danger {
            border: 2px solid #dc3545 !important;
        }
        .text-checked {
            color: #6c757d;
        }
        .badge {
            font-size: 0.8em;
            margin-left: 10px;
        }
        .task-card {
            transition: all 0.3s ease;
        }
        .task-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
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

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <h3 class="text-center text-white">Dashboard</h3>
    <a href="home.php">🏠 Home</a>
    <a href="tasks.php">📝 Tasks</a>
    <a href="profile.php">👤 Profile</a>
</div>

<div class="container mt-5">
    <div class="row">
        <!-- Kolom Kiri: Daftar Task -->
        <div class="col-md-8 center-task-panel" id="task-panel">
            <div class="card bg-secondary text-white p-4 shadow-lg">
                <h2 class="text-center mb-4 text-white">📝 To-Do List</h2>
                
                <!-- Tombol "+" untuk menampilkan form -->
                <button id="addTaskButton" class="btn btn-primary mb-3">Tambah Task</button>

                <!-- Form Tambah Task (awalnya disembunyikan) -->
                <div id="addTaskForm" style="display: none;">
                    <form action="tambah_tasks.php" method="post">
                        <input type="text" name="nama_task" class="form-control mb-2" placeholder="Nama Task" required>
                        <input type="date" name="deadline" class="form-control mb-2" required min="<?= date('Y-m-d') ?>">
                        <select name="prioritas" class="form-control mb-2" required>
                            <option value="" disabled selected>Pilih Prioritas</option>
                            <option value="Tinggi">Tinggi</option>
                            <option value="Sedang">Sedang</option>
                            <option value="Rendah">Rendah</option>
                        </select>
                        <button type="submit" class="btn btn-primary me-3 mb-3 mt-3">Simpan Task</button>
                        <button type="button" class="btn btn-primary" onclick="hideAddTaskForm()">Batal</button>
                    </form>
                </div>

                <!-- Form Filter -->
                <form method="get" class="mb-4">
                    <div class="row">
                        <div class="col-md-4">
                            <select name="prioritas" class="form-control mb-2 text-center">
                                <option value="">Prioritas</option>
                                <option value="Tinggi" <?= $filter_prioritas == 'Tinggi' ? 'selected' : '' ?>>Tinggi</option>
                                <option value="Sedang" <?= $filter_prioritas == 'Sedang' ? 'selected' : '' ?>>Sedang</option>
                                <option value="Rendah" <?= $filter_prioritas == 'Rendah' ? 'selected' : '' ?>>Rendah</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select name="deadline" class="form-control mb-2 text-center">
                                <option value="">Deadline</option>
                                <option value="today" <?= $filter_deadline == 'today' ? 'selected' : '' ?>>Hari Ini</option>
                                <option value="tomorrow" <?= $filter_deadline == 'tomorrow' ? 'selected' : '' ?>>Besok</option>
                                <option value="this_week" <?= $filter_deadline == 'this_week' ? 'selected' : '' ?>>Minggu Ini</option>
                                <option value="next_week" <?= $filter_deadline == 'next_week' ? 'selected' : '' ?>>Minggu Depan</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select name="status" class="form-control mb-2 text-center">
                                <option value="">Status</option>
                                <option value="Belum Selesai" <?= $filter_status == 'Belum Selesai' ? 'selected' : '' ?>>Belum Selesai</option>
                                <option value="Selesai" <?= $filter_status == 'Selesai' ? 'selected' : '' ?>>Selesai</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary me-3">Apply Filter</button>
                    <a href="tasks.php" class="btn btn-primary">Reset Filter</a>
                </form>

                <!-- Daftar Task -->
                <?php while ($task = mysqli_fetch_assoc($run_q_tasks)) { ?>
                    <div class='card mt-3 p-3 task-card <?= $task['deadline_status'] == 'Terlewat' ? 'border-danger' : '' ?>'>
                        <h3 class="<?= $task['status'] == 'Selesai' ? 'text-decoration-line-through text-checked' : '' ?>">
                            <?= htmlspecialchars($task['nama_tugas']) ?>
                            <?php if ($task['deadline_status'] == 'Terlewat' && $task['status'] == 'Belum Selesai'): ?>
                                <span class="badge bg-danger">Deadline Terlewat!</span>
                            <?php endif; ?>
                        </h3>
                        <p>📅 Deadline: <?= htmlspecialchars($task['deadline']) ?> 
                            <?php if ($task['deadline_status'] == 'Terlewat' && $task['status'] == 'Belum Selesai'): ?>
                                <span class="text-danger">(Terlewat!)</span>
                            <?php endif; ?>
                        </p>
                        <p>🔥 Prioritas: <?= htmlspecialchars($task['prioritas']) ?></p>
                        <p>✅ Status: 
                            <select onchange="updateTaskStatus(<?= $task['id'] ?>, this.value)">
                                <option value="Belum Selesai" <?= $task['status'] == 'Belum Selesai' ? 'elected' : '' ?>>Belum Selesai</option>
                                <option value="Selesai" <?= $task['status'] == 'Selesai' ? 'selected' : '' ?>>Selesai</option>
                            </select>
                        </p>
                            <a href="edit_tasks.php?id=<?= $task['id'] ?>" class="btn btn-sm btn-warning m-1">Update Task</a>
                            <a href="javascript:void(0);" class="btn btn-sm btn-danger m-1" 
                            onclick="confirmDelete(<?= $task['id'] ?>)">Delete Task</a>
                                 <button class="btn btn-sm btn-info m-1" onclick="showSubtask(<?= $task['id'] ?>, '<?= htmlspecialchars($task['nama_tugas']) ?>')">Lihat Subtask</button>
                    </div>
                <?php } ?>
            </div>
        </div>

        <!-- Kolom Kanan: Panel Subtask -->
        <div class="col-md-4" id="subtask-panel">
            <div class="card bg-secondary text-white p-4 shadow-lg">
                <h3 class="text-center" id="subtask-title">Subtask</h3>
                <div class="loading-spinner" id="loading-spinner"></div>
                <div id="subtask-content">
                    <!-- Konten subtask akan dimuat di sini via AJAX -->
                </div>
                <button class="btn btn-danger mt-3 btn-custom" onclick="hideSubtask()">Hide Subtask</button>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Fungsi untuk menampilkan/sembunyikan form tambah task
    document.getElementById('addTaskButton').addEventListener('click', function () {
        document.getElementById('addTaskForm').style.display = 'block';
    });

    function hideAddTaskForm() {
        document.getElementById('addTaskForm').style.display = 'none';
    }

    // Fungsi lainnya (update status, konfirmasi hapus, dll.)
    function updateTaskStatus(taskId, newStatus) {
        $.ajax({
            url: 'update_status.php',
            type: 'POST',
            data: { id: taskId, status: newStatus },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert('Status task berhasil diupdate!');
                    location.reload();
                } else {
                    alert('Gagal mengupdate status: ' + response.message);
                }
            },
            error: function() {
                alert('Terjadi kesalahan pada server');
            }
        });
    }

    function confirmDelete(taskId) {
        if (confirm('Apakah kamu yakin ingin menghapus task ini?')) {
            window.location.href = `hapus_tasks.php?id=${taskId}`;
        }
    }

    function confirmLogout() {
        if (confirm('Apakah kamu yakin ingin logout?')) {
            window.location.href = 'logout.php';
        }
    }

    function toggleSidebar() {
        document.getElementById("sidebar").classList.toggle("active");
    }

    function updateClock() {
        let now = new Date();
        let hours = now.getHours().toString().padStart(2, '0');
        let minutes = now.getMinutes().toString().padStart(2, '0');
        let seconds = now.getSeconds().toString().padStart(2, '0');
        document.getElementById("clock").textContent = `🕒 ${hours}:${minutes}:${seconds}`;
    }
    setInterval(updateClock, 1000);
    updateClock();

    function showSubtask(taskId, taskName) {
        document.getElementById('loading-spinner').style.display = 'block';
        document.getElementById('subtask-content').innerHTML = '';
        document.getElementById('subtask-panel').classList.add('show');
        document.getElementById('subtask-panel').style.display = 'block';
        document.getElementById('task-panel').classList.add('hide');

        $.ajax({
            url: 'get_subtasks.php',
            type: 'GET',
            data: { task_id: taskId },
            success: function(response) {
                document.getElementById('loading-spinner').style.display = 'none';
                document.getElementById('subtask-content').innerHTML = response;
                document.getElementById('subtask-title').textContent = `Subtask: ${taskName}`;
            },
            error: function() {
                document.getElementById('loading-spinner').style.display = 'none';
                alert('Gagal memuat subtask!');
            }
        });
    }

    function hideSubtask() {
        document.getElementById('subtask-panel').classList.remove('show');
        document.getElementById('subtask-panel').style.display = 'none';
        document.getElementById('task-panel').classList.remove('hide');
    }
</script>
</body>
</html>