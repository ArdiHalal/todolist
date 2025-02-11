<?php
include 'koneksi.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$list_id = $_GET['id'];
$query = "SELECT * FROM lists WHERE id = '$list_id'";
$result = mysqli_query($conn_todolist, $query);
$list = mysqli_fetch_assoc($result);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_list = mysqli_real_escape_string($conn_todolist, $_POST['nama_list']);
    
    $update_query = "UPDATE lists SET nama_list='$nama_list' WHERE id='$list_id'";
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
    <title>Edit List</title>
    <link rel="stylesheet" href="style1.css">
</head>
<body class="bg-dark text-white">
    <div class="container mt-5">
        <div class="card p-4 bg-secondary text-white">
            <h2 class="text-center">Edit List</h2>
            <form method="POST">
                <label>Nama List:</label>
                <input type="text" class="form-control" name="nama_list" value="<?= htmlspecialchars($list['nama_list']); ?>" required>
                <button type="submit" class="btn btn-success mt-3">Simpan</button>
            </form>
        </div>
    </div>
</body>
</html>