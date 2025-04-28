<?php
// Konfigurasi database
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'todolist_db';

$conn = new mysqli($host, $username, $password);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$sql = "CREATE DATABASE IF NOT EXISTS $database";
if ($conn->query($sql) !== TRUE) {
    die("Error membuat database: " . $conn->error);
}
$conn->select_db($database);

$sql = "CREATE TABLE IF NOT EXISTS crud_051 (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(255) NOT NULL,
    deskripsi TEXT,
    tanggal_selesai DATE NOT NULL,
    prioritas ENUM('rendah', 'sedang', 'tinggi') DEFAULT 'sedang',
    status ENUM('belum_dikerjakan', 'sedang_dikerjakan', 'selesai') DEFAULT 'belum_dikerjakan',
    dibuat_pada TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    diperbarui_pada TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

if ($conn->query($sql) !== TRUE) {
    die("Error membuat tabel: " . $conn->error);
}
?>