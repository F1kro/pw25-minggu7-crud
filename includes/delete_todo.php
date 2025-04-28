<?php
require_once '../config/database.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: ../index.php');
    exit;
}
$id = (int) $_GET['id'];

// Hapus tugas
$query = "DELETE FROM crud_051 WHERE id = $id";
if ($conn->query($query) === TRUE) {
    header('Location: ../index.php?success=dihapus');
    exit;
} else {
    header('Location: ../index.php?error=database_error');
    exit;
}
?>