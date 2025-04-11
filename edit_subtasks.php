<?php
include 'koneksi.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Ambil data subtask
$query = "SELECT * FROM sub_tasks WHERE id = ?";
$stmt = mysqli_prepare($conn_todolist, $query);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$subtask = mysqli_fetch_assoc($result);

// Proses update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_subtask = mysqli_real_escape_string($conn_todolist, $_POST['nama_subtask']);
    $prioritas = $_POST['prioritas'];

    $update_query = "UPDATE sub_tasks SET nama_subtask = ?, prioritas = ? WHERE id = ?";
    $stmt_update = mysqli_prepare($conn_todolist, $update_query);
    mysqli_stmt_bind_param($stmt_update, "ssi", $nama_subtask, $prioritas, $id);
    mysqli_stmt_execute($stmt_update);

    // Redirect dengan notifikasi sukses
    header("Location: tasks.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Subtask</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css"> <!-- CSS eksternal -->
</head>
<body>
<div class="container d-flex justify-content-center">
    <div class="card custom-card p-4 w-100" style="max-width: 500px;">
        <h4 class="mb-3">Edit Subtask</h4>

        <?php if ($subtask): ?>
            <form method="POST">
                <div class="mb-3">
                    <label for="nama_subtask" class="form-label">Nama Subtask:</label>
                    <input type="text" name="nama_subtask" id="nama_subtask"
                           class="form-control" value="<?= htmlspecialchars($subtask['nama_subtask']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="prioritas" class="form-label">Prioritas</label>
                    <select name="prioritas" id="prioritas" class="form-control" required>
                        <option value="Tinggi" <?= $subtask['prioritas'] == 'Tinggi' ? 'selected' : '' ?>>Tinggi</option>
                        <option value="Sedang" <?= $subtask['prioritas'] == 'Sedang' ? 'selected' : '' ?>>Sedang</option>
                        <option value="Rendah" <?= $subtask['prioritas'] == 'Rendah' ? 'selected' : '' ?>>Rendah</option>
                    </select>
                </div>
                <div class="d-flex justify-content-between">
                    <a href="tasks.php" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </form>
        <?php else: ?>
            <div class="alert alert-warning">Subtask tidak ditemukan!</div>
        <?php endif; ?>
    </div>
</div>
</body>
</html>