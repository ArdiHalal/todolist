<?php
include 'koneksi.php';

if (isset($_POST['task_id']) && isset($_POST['nama_task'])) {
    $id = $_POST['task_id'];
    $nama_task = $_POST['nama_task'];
    $deadline = $_POST['deadline'];
    $prioritas = $_POST['prioritas'];

    $stmt = mysqli_prepare($conn_todolist, "UPDATE tasks SET nama_tugas=?, deadline=?, prioritas=? WHERE id=?");
    mysqli_stmt_bind_param($stmt, "sssi", $nama_task, $deadline, $prioritas, $id);
    $execute = mysqli_stmt_execute($stmt);

    if ($execute) {
        header("Location: tasks.php");
        exit;
    } else {
        echo "Gagal mengupdate task.";
    }
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = mysqli_prepare($conn_todolist, "SELECT * FROM tasks WHERE id=?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        $task = mysqli_fetch_assoc($result);
    } else {
        $task = null;
    }
} else {
    $task = null;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Task</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
          background-image: url("Edit.jpg"),
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
<body>
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="card w-100" style="max-width: 600px;">
            <div class="card-body">
                <h4 class="card-title mb-4">Edit Task</h4>

                <?php if ($task): ?>
                <form method="POST">
                    <input type="hidden" name="task_id" value="<?= $task['id'] ?>">

                    <div class="mb-3">
                        <label for="nama_task" class="form-label">Judul Tugas</label>
                        <input type="text" name="nama_task" id="nama_task"
                               class="form-control" value="<?= htmlspecialchars($task['nama_tugas']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="deadline" class="form-label">Tanggal Deadline</label>
                        <input type="date" name="deadline" id="deadline"
                               class="form-control" value="<?= $task['deadline'] ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="prioritas" class="form-label">Prioritas</label>
                        <select name="prioritas" id="prioritas" class="form-control" required>
                            <option value="Tinggi" <?= $task['prioritas'] == 'Tinggi' ? 'selected' : '' ?>>Tinggi</option>
                            <option value="Sedang" <?= $task['prioritas'] == 'Sedang' ? 'selected' : '' ?>>Sedang</option>
                            <option value="Rendah" <?= $task['prioritas'] == 'Rendah' ? 'selected' : '' ?>>Rendah</option>
                        </select>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="tasks.php" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </form>
                <?php else: ?>
                    <div class="alert alert-warning">Task tidak ditemukan!</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
