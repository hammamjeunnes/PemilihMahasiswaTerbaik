<?php
require_once __DIR__ . '/../config/database.php';

// Fungsi untuk mendapatkan data mahasiswa
function getMahasiswaData($conn) {
    $sql = "SELECT * FROM mahasiswa ORDER BY created_at DESC";
    $result = $conn->query($sql);
    
    $data = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }
    return $data;
}

// Fungsi untuk menormalisasi matriks keputusan
function normalizeMatrix($data) {
    $normalized = [];
    $maxValues = [];
    $minValues = [];
    
    // Inisialisasi array untuk menyimpan nilai maksimum dan minimum
    $criteria = ['iq', 'eq', 'attitude', 'association', 'friendship'];
    foreach ($criteria as $criterion) {
        $maxValues[$criterion] = PHP_FLOAT_MIN;
        $minValues[$criterion] = PHP_FLOAT_MAX;
    }
    $minValues['effort_regulation'] = PHP_FLOAT_MAX;
    
    // Mencari nilai maksimum dan minimum untuk setiap kriteria
    foreach ($data as $row) {
        foreach ($criteria as $criterion) {
            $value = (float)$row[$criterion];
            if ($value > $maxValues[$criterion]) {
                $maxValues[$criterion] = $value;
            }
            if ($value < $minValues[$criterion]) {
                $minValues[$criterion] = $value;
            }
        }
        
        // Effort Regulation (cost criteria)
        $value = (float)$row['effort_regulation'];
        if ($value < $minValues['effort_regulation']) {
            $minValues['effort_regulation'] = $value;
        }
    }
    
    // Normalisasi data
    foreach ($data as $row) {
        $normalizedRow = [
            'id' => $row['id'],
            'nama' => $row['nama'],
            'nim' => $row['nim']
        ];
        
        // Normalisasi kriteria benefit (semakin besar semakin baik)
        foreach ($criteria as $criterion) {
            $value = (float)$row[$criterion];
            if ($maxValues[$criterion] == 0) {
                $normalizedRow[$criterion] = 0;
            } else {
                $normalizedRow[$criterion] = $value / $maxValues[$criterion];
            }
        }
        
        // Normalisasi kriteria cost (semakin kecil semakin baik)
        $value = (float)$row['effort_regulation'];
        if ($value == 0) {
            $normalizedRow['effort_regulation'] = 1; // Menghindari pembagian dengan nol
        } else {
            $normalizedRow['effort_regulation'] = $minValues['effort_regulation'] / $value;
        }
        
        $normalized[] = $normalizedRow;
    }
    
    return $normalized;
}

// Fungsi untuk menghitung bobot menggunakan metode WP (Weighted Product)
function calculateWPWeights($data) {
    // Inisialisasi array untuk menyimpan hasil perkalian
    $products = [
        'iq' => 1.0,
        'eq' => 1.0,
        'attitude' => 1.0,
        'association' => 1.0,
        'friendship' => 1.0,
        'effort_regulation' => 1.0
    ];
    
    $n = count($data);
    
    if ($n == 0) {
        // Jika tidak ada data, kembalikan bobot yang sama
        return [
            'iq' => 1/6,
            'eq' => 1/6,
            'attitude' => 1/6,
            'association' => 1/6,
            'friendship' => 1/6,
            'effort_regulation' => 1/6
        ];
    }
    
    // Hitung perkalian untuk setiap kriteria
    foreach ($data as $row) {
        $products['iq'] *= $row['iq'] > 0 ? $row['iq'] : 0.01; // Menghindari nilai 0
        $products['eq'] *= $row['eq'] > 0 ? $row['eq'] : 0.01;
        $products['attitude'] *= $row['attitude'] > 0 ? $row['attitude'] : 0.01;
        $products['association'] *= $row['association'] > 0 ? $row['association'] : 0.01;
        $products['friendship'] *= $row['friendship'] > 0 ? $row['friendship'] : 0.01;
        $products['effort_regulation'] *= $row['effort_regulation'] > 0 ? $row['effort_regulation'] : 0.01;
    }
    
    // Hitung akar pangkat n
    $sum = 0;
    $roots = [];
    foreach ($products as $criterion => $product) {
        $root = pow($product, 1/$n);
        $roots[$criterion] = $root;
        $sum += $root;
    }
    
    // Normalisasi bobot
    $weights = [];
    foreach ($roots as $criterion => $root) {
        $weights[$criterion] = $sum > 0 ? $root / $sum : 1/6; // Jika jumlah 0, beri bobot yang sama
    }
    
    return $weights;
}

// Fungsi untuk menghitung peringkat menggunakan metode SAW
function calculateSAWRankings($normalizedData, $weights) {
    $rankings = [];
    
    foreach ($normalizedData as $row) {
        $score = 0;
        $score += $row['iq'] * $weights['iq'];
        $score += $row['eq'] * $weights['eq'];
        $score += $row['attitude'] * $weights['attitude'];
        $score += $row['association'] * $weights['association'];
        $score += $row['friendship'] * $weights['friendship'];
        $score += $row['effort_regulation'] * $weights['effort_regulation'];
        
        $rankings[] = [
            'id' => $row['id'],
            'nama' => $row['nama'],
            'nim' => $row['nim'],
            'score' => $score
        ];
    }
    
    // Urutkan berdasarkan skor tertinggi
    usort($rankings, function($a, $b) {
        return $b['score'] <=> $a['score'];
    });
    
    return $rankings;
}

// Ambil koneksi database
$conn = getConnection();

// Dapatkan data mahasiswa
$mahasiswaData = getMahasiswaData($conn);

// Normalisasi data
$normalizedData = normalizeMatrix($mahasiswaData);

// Hitung bobot menggunakan WP
$weights = calculateWPWeights($mahasiswaData);

// Hitung peringkat menggunakan SAW
$rankings = calculateSAWRankings($normalizedData, $weights);
?>

<?php include '../includes/header.php'; ?>

<div class="max-w-7xl mx-auto">
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-semibold text-gray-800">
                    <i class="fas fa-trophy text-yellow-500 mr-2"></i>Peringkat Mahasiswa
                </h2>
                <button id="showWeightsBtn" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-weight-hanging mr-2"></i>Tampilkan Bobot
                </button>
            </div>
            
            <!-- Tabel Peringkat -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Peringkat</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIM</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Skor Akhir</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($rankings as $index => $ranking): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <?php if ($index < 3): ?>
                                        <span class="flex items-center justify-center w-8 h-8 rounded-full bg-yellow-100 text-yellow-800 font-bold">
                                            <?php echo $index + 1; ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-gray-700"><?php echo $index + 1; ?></span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($ranking['nama']); ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500"><?php echo htmlspecialchars($ranking['nim']); ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 font-medium">
                                    <?php echo number_format($ranking['score'] * 100, 2); ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex items-center justify-center space-x-4">
                                    <a href="edit.php?id=<?php echo $ranking['id']; ?>" class="text-indigo-600 hover:text-indigo-900" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="delete.php?id=<?php echo $ranking['id']; ?>" class="text-red-600 hover:text-red-900" title="Hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <?php if (empty($rankings)): ?>
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-inbox text-4xl mb-2"></i>
                    <p class="text-lg">Belum ada data mahasiswa</p>
                    <a href="form.php" class="mt-4 inline-flex items-center text-indigo-600 hover:text-indigo-900">
                        <i class="fas fa-plus-circle mr-2"></i>Tambah Data Mahasiswa
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal Bobot -->
<div id="weightsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100">
                <i class="fas fa-weight-hanging text-indigo-600 text-xl"></i>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 mt-3">Bobot Kriteria</h3>
            <div class="mt-4 px-4 py-3">
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">IP (Prestasi Akademik)</span>
                        <span class="text-sm font-medium"><?php echo number_format($weights['iq'] * 100, 2); ?>%</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Kegiatan Sosial</span>
                        <span class="text-sm font-medium"><?php echo number_format($weights['eq'] * 100, 2); ?>%</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Sertifikat</span>
                        <span class="text-sm font-medium"><?php echo number_format($weights['attitude'] * 100, 2); ?>%</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Organisasi</span>
                        <span class="text-sm font-medium"><?php echo number_format($weights['association'] * 100, 2); ?>%</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Interaksi Sosial</span>
                        <span class="text-sm font-medium"><?php echo number_format($weights['friendship'] * 100, 2); ?>%</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Keterlambatan Tugas</span>
                        <span class="text-sm font-medium"><?php echo number_format($weights['effort_regulation'] * 100, 2); ?>%</span>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <p class="text-xs text-gray-500">* Bobot dihitung menggunakan metode Weighted Product (WP) berdasarkan data saat ini.</p>
                </div>
            </div>
            <div class="items-center px-4 py-3">
                <button id="closeModalBtn" class="px-4 py-2 bg-indigo-600 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Script untuk menangani modal bobot
document.getElementById('showWeightsBtn').addEventListener('click', function() {
    document.getElementById('weightsModal').classList.remove('hidden');
});

document.getElementById('closeModalBtn').addEventListener('click', function() {
    document.getElementById('weightsModal').classList.add('hidden');
});

// Tutup modal saat mengklik di luar konten
window.onclick = function(event) {
    const modal = document.getElementById('weightsModal');
    if (event.target == modal) {
        modal.classList.add('hidden');
    }
}
</script>

<?php include '../includes/footer.php'; ?>
