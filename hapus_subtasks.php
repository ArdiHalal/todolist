<?php
include 'koneksi.php';

if (isset($_GET['id'])) {
    $task_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    $q_delete = "DELETE FROM sub_tasks WHERE id='$task_id'";
    mysqli_query($conn_todolist, $q_delete);
}
header("Location: tasks.php");
exit;
?>
