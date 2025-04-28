<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: index.php');
    exit;
}
$id = $_GET['id'];

$todo = getTodoById($conn, $id);

if (!$todo) {
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Tugas - Daftar Tugas</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.all.min.js"></script>
</head>
<body class="min-h-screen bg-gray-100">
    <div class="container px-4 py-8 mx-auto">
        <header class="mb-8">
            <div class="flex items-center justify-between">
                <h1 class="text-3xl font-bold text-indigo-700">Edit Tugas</h1>
                <a href="index.php" class="px-4 py-2 text-white transition-colors duration-300 bg-gray-600 rounded-lg hover:bg-gray-700">
                    Kembali ke Daftar
                </a>
            </div>
        </header>

        <div class="max-w-2xl p-6 mx-auto bg-white rounded-lg shadow-md">
            <form action="includes/update_todo.php" method="POST" id="editTodoForm">
                <input type="hidden" name="id" value="<?php echo $todo['id']; ?>">
                
                <div class="mb-4">
                    <label for="judul" class="block mb-1 text-sm font-medium text-gray-700">Judul</label>
                    <input type="text" name="judul" id="judul" required 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" 
                           value="<?php echo htmlspecialchars($todo['judul']); ?>"
                           placeholder="Masukkan judul tugas">
                </div>
                
                <div class="mb-4">
                    <label for="deskripsi" class="block mb-1 text-sm font-medium text-gray-700">Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsi" rows="4" 
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" 
                              placeholder="Masukkan deskripsi tugas"><?php echo htmlspecialchars($todo['deskripsi']); ?></textarea>
                </div>
                
                <div class="mb-4">
                    <label for="tanggal_selesai" class="block mb-1 text-sm font-medium text-gray-700">Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" id="tanggal_selesai" required 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                           value="<?php echo $todo['tanggal_selesai']; ?>">
                </div>
                
                <div class="mb-4">
                    <label for="prioritas" class="block mb-1 text-sm font-medium text-gray-700">Prioritas</label>
                    <select name="prioritas" id="prioritas" required 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="rendah" <?php echo $todo['prioritas'] === 'rendah' ? 'selected' : ''; ?>>Rendah</option>
                        <option value="sedang" <?php echo $todo['prioritas'] === 'sedang' ? 'selected' : ''; ?>>Sedang</option>
                        <option value="tinggi" <?php echo $todo['prioritas'] === 'tinggi' ? 'selected' : ''; ?>>Tinggi</option>
                    </select>
                </div>
                
                <div class="mb-6">
                    <label for="status" class="block mb-1 text-sm font-medium text-gray-700">Status</label>
                    <select name="status" id="status" required 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="belum_dikerjakan" <?php echo $todo['status'] === 'belum_dikerjakan' ? 'selected' : ''; ?>>Belum Dikerjakan</option>
                        <option value="sedang_dikerjakan" <?php echo $todo['status'] === 'sedang_dikerjakan' ? 'selected' : ''; ?>>Sedang Dikerjakan</option>
                        <option value="selesai" <?php echo $todo['status'] === 'selesai' ? 'selected' : ''; ?>>Selesai</option>
                    </select>
                </div>
                
                <div class="flex justify-end space-x-4">
                    <a href="index.php" class="px-6 py-2 text-white transition-colors duration-300 bg-gray-500 rounded-lg hover:bg-gray-600">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2 text-white transition-colors duration-300 bg-indigo-600 rounded-lg hover:bg-indigo-700">
                        Perbarui Tugas
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>