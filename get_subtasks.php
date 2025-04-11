<?php
include 'koneksi.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Validasi dan sanitasi task_id
if (!isset($_GET['task_id']) || !is_numeric($_GET['task_id'])) {
    die("Error: task_id tidak valid atau tidak dikirim!");
}

$task_id = intval($_GET['task_id']); // Sanitasi task_id

// Form Tambah Subtask
echo '
<form action="tambah_subtasks.php" method="post" class="d-flex mb-3 mt-2">
    <input type="hidden" name="task_id" value="' . htmlspecialchars($task_id) . '">
    <input type="text" class="form-control me-2" name="nama_subtask" placeholder="Nama subtask..." required>
    <select name="prioritas" class="form-select me-2">
        <option value="Tinggi">🔥 Tinggi</option>
        <option value="Sedang">⚡ Sedang</option>
        <option value="Rendah">🌱 Rendah</option>
    </select>
    <button type="submit" name="add" class="btn btn-success">Add</button>
</form>';

// Query untuk mengambil subtask berdasarkan task_id menggunakan prepared statement
$q_subtasks = "SELECT * FROM sub_tasks WHERE task_id = ? ORDER BY 
    CASE 
        WHEN prioritas = 'Tinggi' THEN 1
        WHEN prioritas = 'Sedang' THEN 2
        ELSE 3 
    END";
$stmt = mysqli_prepare($conn_todolist, $q_subtasks);

if (!$stmt) {
    die("Error: Gagal menyiapkan query!");
}

mysqli_stmt_bind_param($stmt, "i", $task_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$result) {
    die("Error: Gagal mengambil data subtask!");
}

// Daftar Subtask
echo '<ul class="list-group">';
while ($subtask = mysqli_fetch_assoc($result)) {
    $subtask_id = htmlspecialchars($subtask['id']);
    $subtask_nama = htmlspecialchars($subtask['nama_subtask']);
    $subtask_status = htmlspecialchars($subtask['status']);
    $subtask_prioritas = htmlspecialchars($subtask['prioritas']);

    // Tentukan kelas CSS berdasarkan prioritas
    $badge_class = '';
    switch ($subtask_prioritas) {
        case 'Tinggi':
            $badge_class = 'bg-danger';
            break;
        case 'Sedang':
            $badge_class = 'bg-warning';
            break;
        case 'Rendah':
            $badge_class = 'bg-success';
            break;
    }

    echo '
    <li class="list-group-item d-flex justify-content-between align-items-center bg-dark text-white mb-2">
        <div>
            <input type="checkbox" class="form-check-input me-2" 
                   onclick="updateSubtaskStatus(' . $subtask_id . ', this)" 
                   ' . ($subtask_status == 'Selesai' ? 'checked' : '') . '>
            <span class="' . ($subtask_status == 'Selesai' ? 'text-decoration-line-through text-light' : '') . '">
                ' . $subtask_nama . '
            </span>
            <span class="badge ' . $badge_class . '">
                ' . $subtask_prioritas . '
            </span>
        </div>
        <div>
            <a href="edit_subtasks.php?id=' . $subtask_id . '" class="btn btn-sm btn-warning">Edit</a>
            <a href="hapus_subtasks.php?id=' . $subtask_id . '" class="btn btn-sm btn-danger" 
               onclick="return confirm(\'Yakin ingin menghapus?\')">Hapus</a>
        </div>
    </li>';
}
echo '</ul>';

// Fungsi JavaScript untuk update status subtask
echo '
<script>
function updateSubtaskStatus(subtaskId, checkbox) {
    const newStatus = checkbox.checked ? "Selesai" : "Belum Selesai";
    fetch("update_subtask_status.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ id: subtaskId, status: newStatus })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert("Status subtask berhasil diupdate!");
            location.reload(); // Reload halaman untuk menampilkan perubahan
        } else {
            alert("Gagal mengupdate status: " + data.message);
        }
    })
    .catch(error => {
        alert("Terjadi kesalahan pada server");
    });
}
</script>';
?>