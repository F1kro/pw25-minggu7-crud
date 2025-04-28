<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

// variabel pencarian
$kataKunci = '';
$filterStatus = '';
$filterPrioritas = '';

// Handle searching + filter
if (isset($_GET['search'])) {
    $kataKunci = $_GET['search'];
}

if (isset($_GET['status']) && $_GET['status'] !== '') {
    $filterStatus = $_GET['status'];
}

if (isset($_GET['priority']) && $_GET['priority'] !== '') {
    $filterPrioritas = $_GET['priority'];
}

// filter tugas
$todos = getTodos($conn, $kataKunci, $filterStatus, $filterPrioritas);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Tugas - Aplikasi CRUD</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cal+Sans&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.all.min.js"></script>
    <style>
        .todo-card {
            transition: all 0.3s ease;
        }
        .todo-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        .priority-tinggi {
            border-left: 4px solid #EF4444;
        }
        .priority-sedang {
            border-left: 4px solid #F59E0B;
        }
        .priority-rendah {
            border-left: 4px solid #10B981;
        }
    </style>
</head>
<body class="min-h-screen font-semibold bg-gray-100 font-poppins">
    <div class="container px-4 py-8 mx-auto">
        <header class="mb-8 text-center">
            <h1 class="mb-2 text-3xl font-bold text-indigo-700 md:text-4xl font-poppins">Eh-Mas-Todo-List</h1>
            <p class="text-gray-600">Kelola Tugas Kelen Disini!</p>
        </header>
        <div class="p-4 mb-6 bg-white rounded-lg shadow-md">
            <form action="index.php" method="GET" class="flex flex-col gap-4 md:flex-row">
                <div class="flex-grow">
                    <input type="text" name="search" value="<?php echo htmlspecialchars($kataKunci); ?>"
                           placeholder="Cari tugas..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div class="flex flex-col gap-4 sm:flex-row">
                    <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">Semua Status</option>
                        <option value="belum_dikerjakan" <?php echo $filterStatus === 'belum_dikerjakan' ? 'selected' : ''; ?>>Belum Dikerjakan</option>
                        <option value="sedang_dikerjakan" <?php echo $filterStatus === 'sedang_dikerjakan' ? 'selected' : ''; ?>>Sedang Dikerjakan</option>
                        <option value="selesai" <?php echo $filterStatus === 'selesai' ? 'selected' : ''; ?>>Selesai</option>
                    </select>
                    <select name="priority" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">Semua Prioritas</option>
                        <option value="rendah" <?php echo $filterPrioritas === 'rendah' ? 'selected' : ''; ?>>Rendah</option>
                        <option value="sedang" <?php echo $filterPrioritas === 'sedang' ? 'selected' : ''; ?>>Sedang</option>
                        <option value="tinggi" <?php echo $filterPrioritas === 'tinggi' ? 'selected' : ''; ?>>Tinggi</option>
                    </select>
                    <button type="submit" class="px-6 py-2 text-white transition-colors duration-300 bg-indigo-600 rounded-lg hover:bg-indigo-700">
                        Filter
                    </button>
                </div>
            </form>
        </div>

        <div class="flex flex-col gap-6 md:flex-row">
            <div class="w-full p-6 bg-white rounded-lg shadow-md md:w-1/3">
                <h2 class="mb-4 text-xl font-semibold text-gray-800">Tambah Tugas Baru</h2>
                <form action="includes/add_todo.php" method="POST" id="todoForm">
                    <div class="mb-4">
                        <label for="judul" class="block mb-1 text-sm font-medium text-gray-700">Judul</label>
                        <input type="text" name="judul" id="judul" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                               placeholder="Masukkan judul tugas">
                    </div>
                    <div class="mb-4">
                        <label for="deskripsi" class="block mb-1 text-sm font-medium text-gray-700">Deskripsi</label>
                        <textarea name="deskripsi" id="deskripsi" rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                  placeholder="Masukkan deskripsi tugas"></textarea>
                    </div>
                    <div class="mb-4">
                        <label for="tanggal_selesai" class="block mb-1 text-sm font-medium text-gray-700">Tanggal Selesai</label>
                        <input type="date" name="tanggal_selesai" id="tanggal_selesai" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div class="mb-4">
                        <label for="prioritas" class="block mb-1 text-sm font-medium text-gray-700">Prioritas</label>
                        <select name="prioritas" id="prioritas" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="rendah">Rendah</option>
                            <option value="sedang">Sedang</option>
                            <option value="tinggi">Tinggi</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="status" class="block mb-1 text-sm font-medium text-gray-700">Status</label>
                        <select name="status" id="status" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="belum_dikerjakan">Belum Dikerjakan</option>
                            <option value="sedang_dikerjakan">Sedang Dikerjakan</option>
                            <option value="selesai">Selesai</option>
                        </select>
                    </div>
                    <button type="submit" class="w-full px-4 py-2 text-white transition-colors duration-300 bg-indigo-600 rounded-lg hover:bg-indigo-700">
                        Tambah Tugas
                    </button>
                </form>
            </div>

            <div class="w-full md:w-2/3">
                <?php if (count($todos) > 0): ?>
                    <div class="grid grid-cols-1 gap-4">
                        <?php foreach ($todos as $todo): ?>
                            <div class="todo-card bg-white rounded-lg shadow-md p-5 priority-<?php echo $todo['prioritas']; ?>">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-semibold text-gray-800"><?php echo htmlspecialchars($todo['judul']); ?></h3>
                                        <p class="mt-1 text-gray-600"><?php echo htmlspecialchars($todo['deskripsi']); ?></p>
                                        <div class="flex flex-wrap items-center gap-2 mt-3">
                                            <span class="text-sm text-gray-500">Selesai: <?php echo formatTanggal($todo['tanggal_selesai']); ?></span>

                                            <?php
// Badge status cihuyyy
$statusClass = '';
switch ($todo['status']) {
    case 'selesai':
        $statusClass = 'bg-green-100 text-green-800';
        break;
    case 'sedang_dikerjakan':
        $statusClass = 'bg-blue-100 text-blue-800';
        break;
    default:
        $statusClass = 'bg-yellow-100 text-yellow-800';
}
?>

                                            <span class="px-2 py-1 text-xs rounded-full <?php echo $statusClass; ?>">
                                                <?php
$status = str_replace('_', ' ', $todo['status']);
echo ucwords($status);
?>
                                            </span>

                                            <?php
// Badge prioritas juga cihuyy
$priorityClass = '';
switch ($todo['prioritas']) {
    case 'tinggi':
        $priorityClass = 'bg-red-100 text-red-800';
        break;
    case 'sedang':
        $priorityClass = 'bg-yellow-100 text-yellow-800';
        break;
    default:
        $priorityClass = 'bg-green-100 text-green-800';
}
?>

                                            <span class="px-2 py-1 text-xs rounded-full <?php echo $priorityClass; ?>">
                                                <?php echo ucfirst($todo['prioritas']); ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <a href="edit.php?id=<?php echo $todo['id']; ?>"
                                           class="inline-flex items-center justify-center w-8 h-8 text-indigo-600 transition-colors duration-300 bg-indigo-100 rounded-full hover:bg-indigo-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                            </svg>
                                        </a>
                                        <button onclick="konfirmasiHapus(<?php echo $todo['id']; ?>)"
                                                class="inline-flex items-center justify-center w-8 h-8 text-red-600 transition-colors duration-300 bg-red-100 rounded-full hover:bg-red-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="p-8 text-center bg-white rounded-lg shadow-md">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-16 h-16 mx-auto mb-4 text-gray-400">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                        </svg>
                        <h3 class="mb-2 text-xl font-semibold text-gray-800">Tidak ada tugas</h3>
                        <p class="text-gray-600">Tambahkan tugas pertama Anda untuk memulai!</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        function konfirmasiHapus(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "kalo udah di hapus gabakal balik!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#4F46E5',
                cancelButtonColor: '#EF4444',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `includes/delete_todo.php?id=${id}`;
                }
            });
        }

        <?php if (isset($_GET['success']) && $_GET['success'] === 'ditambahkan'): ?>
            Swal.fire({
                title: 'Berhasil!',
                text: 'Tugas Loe udah ditambahin.',
                icon: 'success',
                confirmButtonColor: '#4F46E5'
            });
        <?php endif; ?>

        <?php if (isset($_GET['success']) && $_GET['success'] === 'diperbarui'): ?>
            Swal.fire({
                title: 'Berhasil!',
                text: 'Tugas Loe udah diperbarui.',
                icon: 'success',
                confirmButtonColor: '#4F46E5'
            });
        <?php endif; ?>

        <?php if (isset($_GET['success']) && $_GET['success'] === 'dihapus'): ?>
            Swal.fire({
                title: 'Terhapus!',
                text: 'Tugas Loe udah dihapus.',
                icon: 'success',
                confirmButtonColor: '#4F46E5'
            });
        <?php endif; ?>
    </script>
</body>
</html>