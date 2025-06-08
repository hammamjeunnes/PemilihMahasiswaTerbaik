<?php
require_once __DIR__ . '/../config/database.php';

// Inisialisasi pesan error
$errors = [];
$success = false;

// Proses form jika ada data yang dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validasi input
    $nama = trim($_POST['nama'] ?? '');
    $nim = trim($_POST['nim'] ?? '');
    $iq = filter_input(INPUT_POST, 'iq', FILTER_VALIDATE_FLOAT);
    $eq = filter_input(INPUT_POST, 'eq', FILTER_VALIDATE_INT);
    $attitude = filter_input(INPUT_POST, 'attitude', FILTER_VALIDATE_INT);
    $association = filter_input(INPUT_POST, 'association', FILTER_VALIDATE_INT);
    $friendship = filter_input(INPUT_POST, 'friendship', FILTER_VALIDATE_INT);
    $effort_regulation = filter_input(INPUT_POST, 'effort_regulation', FILTER_VALIDATE_INT);
    
    // Validasi
    if (empty($nama)) {
        $errors[] = "Nama lengkap harus diisi";
    }
    
    if (empty($nim)) {
        $errors[] = "NIM harus diisi";
    }
    
    if ($iq === false || $iq < 0 || $iq > 4) {
        $errors[] = "IP harus berupa angka antara 0.00 - 4.00";
    }
    
    if ($eq === false || $eq < 0) {
        $errors[] = "Jumlah kegiatan sosial tidak valid";
    }
    
    if ($attitude === false || $attitude < 0) {
        $errors[] = "Jumlah sertifikat tidak valid";
    }
    
    if ($association === false || $association < 0) {
        $errors[] = "Jumlah organisasi tidak valid";
    }
    
    if ($friendship === false || $friendship < 0) {
        $errors[] = "Jumlah kegiatan bersama teman tidak valid";
    }
    
    if ($effort_regulation === false || $effort_regulation < 0) {
        $errors[] = "Jumlah keterlambatan tugas tidak valid";
    }
    
    // Jika tidak ada error, simpan ke database
    if (empty($errors)) {
        $conn = getConnection();
        $nama = $conn->real_escape_string($nama);
        $nim = $conn->real_escape_string($nim);
        
        $sql = "INSERT INTO mahasiswa (nama, nim, iq, eq, attitude, association, friendship, effort_regulation) 
                VALUES ('$nama', '$nim', $iq, $eq, $attitude, $association, $friendship, $effort_regulation)";
                
        if ($conn->query($sql) === TRUE) {
            header("Location: ranking.php?success=1");
            exit();
        } else {
            $errors[] = "Terjadi kesalahan saat menyimpan data: " . $conn->error;
        }
    }
}
?>

<?php include '../includes/header.php'; ?>

<div class="max-w-4xl mx-auto">
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h2 class="text-2xl font-semibold text-gray-800 mb-6">
                <i class="fas fa-edit text-indigo-600 mr-2"></i>Formulir Penilaian Mahasiswa
            </h2>
            
            <?php if (!empty($errors)): ?>
                <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-400 text-xl"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Terdapat kesalahan:</h3>
                            <div class="mt-2 text-sm text-red-700">
                                <ul class="list-disc pl-5 space-y-1">
                                    <?php foreach ($errors as $error): ?>
                                        <li><?php echo htmlspecialchars($error); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            
            <form action="" method="POST" class="space-y-6">
                <!-- Informasi Pribadi -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        <i class="fas fa-user-circle text-indigo-600 mr-2"></i>Informasi Pribadi
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="nama" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                            <input type="text" name="nama" id="nama" required
                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                   value="<?php echo htmlspecialchars($_POST['nama'] ?? ''); ?>">
                        </div>
                        
                        <div>
                            <label for="nim" class="block text-sm font-medium text-gray-700">NIM</label>
                            <input type="text" name="nim" id="nim" required
                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                   value="<?php echo htmlspecialchars($_POST['nim'] ?? ''); ?>">
                        </div>
                    </div>
                </div>
                
                <!-- Kriteria Penilaian -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        <i class="fas fa-clipboard-check text-indigo-600 mr-2"></i>Kriteria Penilaian
                    </h3>
                    
                    <div class="space-y-6">
                        <!-- IQ: Prestasi Akademik -->
                        <div class="bg-white p-4 rounded-lg border border-gray-200">
                            <label for="iq" class="block text-sm font-medium text-gray-700 mb-1">
                                1. IP (Indeks Prestasi)
                            </label>
                            <p class="text-sm text-gray-500 mb-3">Berapa IP Anda pada semester terakhir? (0.00 - 4.00)</p>
                            <input type="number" name="iq" id="iq" step="0.01" min="0" max="4" required
                                   class="block w-32 border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                   value="<?php echo htmlspecialchars($_POST['iq'] ?? ''); ?>">
                        </div>
                        
                        <!-- EQ: Kontribusi Sosial -->
                        <div class="bg-white p-4 rounded-lg border border-gray-200">
                            <label for="eq" class="block text-sm font-medium text-gray-700 mb-1">
                                2. Kegiatan Sosial
                            </label>
                            <p class="text-sm text-gray-500 mb-3">Berapa jumlah kegiatan sosial yang pernah Anda ikuti sebagai mahasiswa? (contoh: menjadi relawan, advokasi masyarakat, atau kampanye sosial)</p>
                            <input type="number" name="eq" id="eq" min="0" required
                                   class="block w-32 border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                   value="<?php echo htmlspecialchars($_POST['eq'] ?? ''); ?>">
                        </div>
                        
                        <!-- Attitude: Pengembangan Diri -->
                        <div class="bg-white p-4 rounded-lg border border-gray-200">
                            <label for="attitude" class="block text-sm font-medium text-gray-700 mb-1">
                                3. Sertifikat Lomba/Pelatihan
                            </label>
                            <p class="text-sm text-gray-500 mb-3">Berapa jumlah sertifikat lomba dan atau pelatihan/kursus (online atau offline) yang Anda miliki?</p>
                            <input type="number" name="attitude" id="attitude" min="0" required
                                   class="block w-32 border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                   value="<?php echo htmlspecialchars($_POST['attitude'] ?? ''); ?>">
                        </div>
                        
                        <!-- Association: Keterlibatan Organisasi -->
                        <div class="bg-white p-4 rounded-lg border border-gray-200">
                            <label for="association" class="block text-sm font-medium text-gray-700 mb-1">
                                4. Keterlibatan Organisasi
                            </label>
                            <p class="text-sm text-gray-500 mb-3">Berapa jumlah organisasi, komunitas, dan atau klub mahasiswa yang pernah Anda ikuti sebagai mahasiswa?</p>
                            <input type="number" name="association" id="association" min="0" required
                                   class="block w-32 border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                   value="<?php echo htmlspecialchars($_POST['association'] ?? ''); ?>">
                        </div>
                        
                        <!-- Friendship: Interaksi dengan Teman -->
                        <div class="bg-white p-4 rounded-lg border border-gray-200">
                            <label for="friendship" class="block text-sm font-medium text-gray-700 mb-1">
                                5. Interaksi Sosial
                            </label>
                            <p class="text-sm text-gray-500 mb-3">Berapa kali Anda mengikuti kegiatan bersama teman dalam semester ini?</p>
                            <input type="number" name="friendship" id="friendship" min="0" required
                                   class="block w-32 border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                   value="<?php echo htmlspecialchars($_POST['friendship'] ?? ''); ?>">
                        </div>
                        
                        <!-- Effort Regulation: Disiplin dan Manajemen Waktu -->
                        <div class="bg-white p-4 rounded-lg border border-gray-200">
                            <label for="effort_regulation" class="block text-sm font-medium text-gray-700 mb-1">
                                6. Keterlambatan Tugas
                            </label>
                            <p class="text-sm text-gray-500 mb-3">Berapa banyak tugas yang Anda serahkan dengan terlambat dalam semester ini?</p>
                            <input type="number" name="effort_regulation" id="effort_regulation" min="0" required
                                   class="block w-32 border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                   value="<?php echo htmlspecialchars($_POST['effort_regulation'] ?? '0'); ?>">
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end">
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-paper-plane mr-2"></i>Kirim Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
