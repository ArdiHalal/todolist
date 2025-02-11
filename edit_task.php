<?php
include 'koneksi.php';

$id = isset($_GET['id']) ? $_GET['id'] : 0;
$query = "SELECT * FROM tasks WHERE id = '$id'";
$result = mysqli_query($conn_todolist, $query);
$tugas = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_tugas = mysqli_real_escape_string($conn_todolist, $_POST['nama_tugas']);
    $tanggal = $_POST['tanggal'];
    $status = $_POST['status'];
    $prioritas = $_POST['prioritas'];
    
    $update_query = "UPDATE tasks SET nama_tugas='$nama_tugas', tanggal='$tanggal', status='$status', prioritas='$prioritas' WHERE id='$id'";
    mysqli_query($conn_todolist, $update_query);
    header("Location: index2.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Tugas</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            background-color: #121212;
            color: white;
            font-family: Arial, sans-serif;
        }
        .container {
            width: 50%;
            margin: auto;
            padding: 20px;
            background-color: #1e1e1e;
            border-radius: 5px;
        }
        input, select, button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Tugas</h2>
        <form method="POST">
            <label>Nama Tugas:</label>
            <input type="text" name="nama_tugas" value="<?php echo $tugas['nama_tugas']; ?>" required>
            
            <label>Tanggal:</label>
            <input type="date" name="tanggal" value="<?php echo $tugas['tanggal']; ?>" required>
            
            <label>Status:</label>
            <select name="status">
                <option value="Belum" <?php if ($tugas['status'] == 'Belum') echo 'selected'; ?>>Belum</option>
                <option value="Sudah" <?php if ($tugas['status'] == 'Sudah') echo 'selected'; ?>>Sudah</option>
            </select>
            
            <label>Prioritas:</label>
            <select name="prioritas">
                <option value="Rendah" <?php if ($tugas['prioritas'] == 'Rendah') echo 'selected'; ?>>Rendah</option>
                <option value="Sedang" <?php if ($tugas['prioritas'] == 'Sedang') echo 'selected'; ?>>Sedang</option>
                <option value="Tinggi" <?php if ($tugas['prioritas'] == 'Tinggi') echo 'selected'; ?>>Tinggi</option>
            </select>
            
            <button type="submit">Simpan</button>
        </form>
    </div>
</body>
</html>
