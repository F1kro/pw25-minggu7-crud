<?php
// dapetin semua tugas +  pencarian + filter opsional
function getTodos($conn, $pencarian = '', $status = '', $prioritas = '') {
    $query = "SELECT * FROM crud_051 WHERE 1=1";
    
    if (!empty($pencarian)) {
        $pencarian = $conn->real_escape_string($pencarian);
        $query .= " AND (judul LIKE '%$pencarian%' OR deskripsi LIKE '%$pencarian%')";
    }
    
    if (!empty($status)) {
        $status = $conn->real_escape_string($status);
        $query .= " AND status = '$status'";
    }
    
    if (!empty($prioritas)) {
        $prioritas = $conn->real_escape_string($prioritas);
        $query .= " AND prioritas = '$prioritas'";
    }
    
    $query .= " ORDER BY tanggal_selesai ASC, FIELD(prioritas, 'tinggi', 'sedang', 'rendah')";
    
    $result = $conn->query($query);
    
    if (!$result) {
        die("Error mengambil data tugas: " . $conn->error);
    }
    
    $todos = [];
    while ($row = $result->fetch_assoc()) {
        $todos[] = $row;
    }
    
    return $todos;
}

// dapetin satu tugas pake id
function getTodoById($conn, $id) {
    $id = (int) $id;
    $query = "SELECT * FROM crud_051 WHERE id = $id";
    $result = $conn->query($query);
    
    if (!$result) {
        die("Error mengambil data tugas: " . $conn->error);
    }
    
    return $result->fetch_assoc();
}

// Format tanggal biar enak dibaca
function formatTanggal($tanggal) {
    $bulan = array(
        1 => 'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember'
    );

    $split = explode('-', $tanggal);
    return $split[2] . ' ' . $bulan[(int)$split[1]] . ' ' . $split[0];
}

// dapetin jumlah status untuk statistik
function getStatusCount($conn) {
    $query = "SELECT status, COUNT(*) as jumlah FROM crud_051 GROUP BY status";
    $result = $conn->query($query);
    
    if (!$result) {
        die("Error mengambil jumlah status: " . $conn->error);
    }
    
    $jumlah = [
        'belum_dikerjakan' => 0,
        'sedang_dikerjakan' => 0,
        'selesai' => 0
    ];
    
    while ($row = $result->fetch_assoc()) {
        $jumlah[$row['status']] = (int) $row['jumlah'];
    }
    
    return $jumlah;
}

// dapetin tugas yang akan segera jatuh tempo (dalam 3 hari)
function getTugasSegera($conn) {
    $query = "SELECT * FROM crud_051 
              WHERE tanggal_selesai BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 DAY) 
              AND status != 'selesai' 
              ORDER BY tanggal_selesai ASC";
    
    $result = $conn->query($query);
    
    if (!$result) {
        die("Error mengambil tugas segera: " . $conn->error);
    }
    
    $todos = [];
    while ($row = $result->fetch_assoc()) {
        $todos[] = $row;
    }
    
    return $todos;
}

// Cek kalo exp
function sudahLewatWaktu($tanggalSelesai) {
    return strtotime($tanggalSelesai) < strtotime(date('Y-m-d')) && $tanggalSelesai != date('Y-m-d');
}
?>