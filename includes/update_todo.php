<?php
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validasi input
    if (empty($_POST['id']) || empty($_POST['judul']) || empty($_POST['tanggal_selesai'])) {
        header('Location: ../index.php?error=data_tidak_lengkap');
        exit;
    }
    
    // Sanitasi input
    $id = (int) $_POST['id'];
    $judul = $conn->real_escape_string($_POST['judul']);
    $deskripsi = $conn->real_escape_string($_POST['deskripsi'] ?? '');
    $tanggal_selesai = $conn->real_escape_string($_POST['tanggal_selesai']);
    $prioritas = $conn->real_escape_string($_POST['prioritas'] ?? 'sedang');
    $status = $conn->real_escape_string($_POST['status'] ?? 'belum_dikerjakan');
    
    // Update tugas
    $query = "UPDATE crud_051 SET 
              judul = '$judul', 
              deskripsi = '$deskripsi', 
              tanggal_selesai = '$tanggal_selesai', 
              prioritas = '$prioritas', 
              status = '$status' 
              WHERE id = $id";
    
    if ($conn->query($query) === TRUE) {
        header('Location: ../index.php?success=diperbarui');
        exit;
    } else {
        header('Location: ../index.php?error=database_error');
        exit;
    }
} else {
    header('Location: ../index.php');
    exit;
}
?>