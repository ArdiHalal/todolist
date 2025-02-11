<?php
include 'koneksi.php';

if (isset($_GET['id']) && isset($_GET['status']) && isset($_SESSION['user_id'])) {
    $task_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    $status = ($_GET['status'] == 'Belum selesai') ? 'Selesai' : 'Belum selesai';

    $q_update = "UPDATE tasks SET status='$status' WHERE id='$task_id' AND user_id='$user_id'";
    mysqli_query($conn_todolist, $q_update);
}

header("Location: index.php");
?><?php
include 'koneksi.php';

if (isset($_GET['id'])) {
    $task_id = $_GET['id'];

    // Ambil status saat ini dari database
    $query = "SELECT status FROM tasks WHERE id='$task_id'";
    $result = mysqli_query($conn_todolist, $query);
    $task = mysqli_fetch_assoc($result);

    if ($task) {
        // Ubah status: jika 'Belum', jadi 'Selesai', dan sebaliknya
        $new_status = ($task['status'] == 'Belum selesai') ? 'Selesai' : 'Belum selesai';
        $update_query = "UPDATE tasks SET status='$new_status' WHERE id='$task_id'";
        mysqli_query($conn_todolist, $update_query);
    }
}

// Kembali ke halaman utama setelah update
header("Location: index2.php");
exit;
?>
