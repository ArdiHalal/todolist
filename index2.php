<?php
include 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login2.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$q_lists = "SELECT * FROM lists WHERE user_id='$user_id'";
$run_q_lists = mysqli_query($conn_todolist, $q_lists);
?>
    
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do List ardita</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style1.css">
    <script>
        function toggleTasks(listId) {
            let taskContainer = document.getElementById('tasks-' + listId);
            if (taskContainer.style.display === 'none' || taskContainer.style.display === '') {
                taskContainer.style.display = 'block';
            } else {
                taskContainer.style.display = 'none';
            }
        }
    </script>
</head>
<body class="bg-dark text-white">
<div class="container mt-5">
    <div class="card bg-secondary text-white p-4 shadow-lg">
        <h2 class="text-center mb-4">📝 To-Do List</h2>
        
        <!-- Form Tambah List -->
        <form action="tambah_list.php" method="post">
            <input type="text" name="nama_list" class="form-control" placeholder="Nama List" required>
            <button type="submit" class="btn btn-primary mt-2">Tambah List</button>
        </form>
        
        <!-- Tombol Hapus Semua List -->
        <form action="hapus_semua_list.php" method="post" class="mt-3">
            <button type="submit" name="delete_all" class="btn btn-danger">Hapus Semua List</button>
        </form>
        
        <?php while ($list = mysqli_fetch_assoc($run_q_lists)) { ?>
            <div class='card mt-3 p-3'>
                <h3 onclick="toggleTasks(<?= $list['id'] ?>)" style="cursor: pointer;">▶ <?= htmlspecialchars($list['nama_list']) ?></h3>
                
                <!-- Tombol Edit & Hapus List -->
                <a href="edit_list.php?id=<?= $list['id'] ?>" class="btn btn-sm btn-warning">Edit List</a>
                <a href="hapus_list.php?id=<?= $list['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus list ini?')">Hapus List</a>
                
                <!-- Container Task -->
                <div id="tasks-<?= $list['id'] ?>" style="display: none; margin-top: 10px;">
                    <!-- Form Tambah Task -->
                    <form action="tambah_task.php" method="post" class="d-flex mb-3 mt-2">
                        <input type="hidden" name="list_id" value="<?= $list['id'] ?>">
                        <input type="text" class="form-control me-2" name="task" placeholder="Tambahkan tugas..." required>
                        <select name="prioritas" class="form-select me-2">
                            <option value="Tinggi">🔥 Tinggi</option>
                            <option value="Sedang">⚡ Sedang</option>
                            <option value="Rendah">🌱 Rendah</option>
                        </select>
                        <button type="submit" name="add" class="btn btn-success">Tambah</button>
                    </form>
                    
                    <ul class="list-group">
                        <?php 
                        $list_id = $list['id'];
                        $q_tasks = "SELECT * FROM tasks WHERE list_id='$list_id' ORDER BY 
                            CASE 
                                WHEN prioritas = 'Tinggi' THEN 1
                                WHEN prioritas = 'Sedang' THEN 2
                                ELSE 3 
                            END";
                        $run_q_tasks = mysqli_query($conn_todolist, $q_tasks);
                        while ($task = mysqli_fetch_assoc($run_q_tasks)) { ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center bg-dark text-white">
                                <div>
                                    <input type="checkbox" class="form-check-input me-2" 
                                        onclick="window.location.href='update_status.php?id=<?= $task['id'] ?>'"
                                        <?= $task['status'] == 'Selesai' ? 'checked' : '' ?>>
                                    <span class="<?= $task['status'] == 'Selesai' ? 'text-decoration-line-through text-muted' : '' ?>">
                                        <?= htmlspecialchars($task['nama_tugas']) ?>
                                    </span>
                                    <span class="badge 
                                        <?= $task['prioritas'] == 'Tinggi' ? 'bg-danger' : ($task['prioritas'] == 'Sedang' ? 'bg-warning' : 'bg-success') ?>">
                                        <?= htmlspecialchars($task['prioritas']) ?>
                                    </span>
                                </div>
                                <div>
                                    <a href="edit.php?id=<?= $task['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                    <a href="hapus.php?id=<?= $task['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                                </div>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        <?php } ?>
        
        <div class="text-center mt-3">
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
