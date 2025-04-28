<?php
require_once '../config/database.php';

// Cek apakah form sudah disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['judul']) || empty($_POST['tanggal_selesai'])) {
        header('Location: ../index.php?error=data_tidak_lengkap');
        exit;
    }
    // Sanitasi input buat aman aja cihuyyy
    $judul = $conn->real_escape_string($_POST['judul']);
    $deskripsi = $conn->real_escape_string($_POST['deskripsi'] ?? '');
    $tanggal_selesai = $conn->real_escape_string($_POST['tanggal_selesai']);
    $prioritas = $conn->real_escape_string($_POST['prioritas'] ?? 'sedang');
    $status = $conn->real_escape_string($_POST['status'] ?? 'belum_dikerjakan');
    
    // Tambah tugas
    $query = "INSERT INTO crud_051 (judul, deskripsi, tanggal_selesai, prioritas, status) 
              VALUES ('$judul', '$deskripsi', '$tanggal_selesai', '$prioritas', '$status')";
    
    if ($conn->query($query) === TRUE) {
        header('Location: ../index.php?success=ditambahkan');
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