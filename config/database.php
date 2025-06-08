<?php
// Konfigurasi database
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'pemilih_mahasiswa_terbaik');

// Mencoba menghubungkan ke database MySQL
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Membuat database jika belum ada
$sql = "CREATE DATABASE IF NOT EXISTS " . DB_NAME;
if ($conn->query($sql) === FALSE) {
    die("Gagal membuat database: " . $conn->error);
}

// Memilih database
$conn->select_db(DB_NAME);

// Membuat tabel mahasiswa jika belum ada
$sql = "CREATE TABLE IF NOT EXISTS mahasiswa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    nim VARCHAR(20) NOT NULL,
    iq DECIMAL(3,2) NOT NULL,
    eq INT NOT NULL,
    attitude INT NOT NULL,
    association INT NOT NULL,
    friendship INT NOT NULL,
    effort_regulation INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === FALSE) {
    die("Gagal membuat tabel: " . $conn->error);
}

// Fungsi untuk mengimpor data awal jika tabel kosong
function importInitialData($conn) {
    $result = $conn->query("SELECT COUNT(*) as total FROM mahasiswa");
    $row = $result->fetch_assoc();
    
    if ($row['total'] == 0) {
        $csvFile = __DIR__ . '/../PemilihanMahasiswaTerbaik_InitialDataset.csv';
        if (($handle = fopen($csvFile, "r")) !== FALSE) {
            $header = fgetcsv($handle, 1000, ",");
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $nama = $conn->real_escape_string($data[0]);
                $nim = $conn->real_escape_string($data[1]);
                $iq = floatval($data[2]);
                $eq = intval($data[3]);
                $attitude = intval($data[4]);
                $association = intval($data[5]);
                $friendship = intval($data[6]);
                $effort_regulation = intval($data[7]);
                
                $sql = "INSERT INTO mahasiswa (nama, nim, iq, eq, attitude, association, friendship, effort_regulation) 
                        VALUES ('$nama', '$nim', $iq, $eq, $attitude, $association, $friendship, $effort_regulation)";
                $conn->query($sql);
            }
            fclose($handle);
        }
    }
}

// Panggil fungsi import data awal
importInitialData($conn);

// Fungsi untuk mendapatkan koneksi database
function getConnection() {
    global $conn;
    return $conn;
}
?>
