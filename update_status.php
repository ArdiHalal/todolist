<?php
include 'koneksi.php';

// Validasi session
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// Validasi input
if (!isset($_POST['id']) || !isset($_POST['status'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit;
}

// Sanitize input
$task_id = (int)$_POST['id'];
$new_status = $_POST['status'];

// Validasi nilai status
$allowed_statuses = ['Belum Selesai', 'Selesai'];
if (!in_array($new_status, $allowed_statuses)) {
    echo json_encode(['success' => false, 'message' => 'Status tidak valid']);
    exit;
}

// Mulai transaction
mysqli_begin_transaction($conn_todolist);

try {
    if ($new_status == 'Selesai') {
        // 1. Ambil data task
        $stmt = mysqli_prepare($conn_todolist, 
            "SELECT * FROM tasks WHERE id=? AND user_id=?"
        );
        mysqli_stmt_bind_param($stmt, "ii", $task_id, $_SESSION['user_id']);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $task = mysqli_fetch_assoc($result);

        if (!$task) {
            throw new Exception('Task tidak ditemukan atau bukan milik Anda');
        }

        // 2. Masukkan ke tabel completed_task
        $stmt_insert = mysqli_prepare($conn_todolist, 
            "INSERT INTO completed_tasks 
            (task_id, user_id, nama_tugas, deadline, prioritas, status, completed_at) 
            VALUES (?, ?, ?, ?, ?, ?, NOW())"
        );
        mysqli_stmt_bind_param(
            $stmt_insert, 
            "iissss", 
            $task_id,
            $_SESSION['user_id'],
            $task['nama_tugas'],
            $task['deadline'],
            $task['prioritas'],
            $new_status
        );
        
        if (!mysqli_stmt_execute($stmt_insert)) {
            throw new Exception('Gagal menyimpan riwayat task');
        }

        // 3. Hapus dari tabel tasks
        $stmt_delete = mysqli_prepare($conn_todolist, 
            "DELETE FROM tasks WHERE id=? AND user_id=?"
        );
        mysqli_stmt_bind_param($stmt_delete, "ii", $task_id, $_SESSION['user_id']);
        
        if (!mysqli_stmt_execute($stmt_delete)) {
            throw new Exception('Gagal menghapus task');
        }
    } else {
        // Update status biasa
        $stmt_update = mysqli_prepare($conn_todolist, 
            "UPDATE tasks SET status=? WHERE id=? AND user_id=?"
        );
        mysqli_stmt_bind_param($stmt_update, "sii", $new_status, $task_id, $_SESSION['user_id']);
        
        if (!mysqli_stmt_execute($stmt_update)) {
            throw new Exception('Gagal mengupdate status');
        }
    }

    // Commit transaction jika semua sukses
    mysqli_commit($conn_todolist);
    echo json_encode(['success' => true]);
    
} catch (Exception $e) {
    // Rollback jika ada error
    mysqli_rollback($conn_todolist);
    echo json_encode([
        'success' => false, 
        'message' => $e->getMessage()
    ]);
}
?>