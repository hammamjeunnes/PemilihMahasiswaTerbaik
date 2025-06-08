<?php
require_once __DIR__ . '/../config/database.php';

// Check if ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: ranking.php');
    exit();
}

$id = $_GET['id'];
$conn = getConnection();

// Get student data
$sql = "SELECT * FROM mahasiswa WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: ranking.php?error=notfound');
    exit();
}

$student = $result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $nama = $_POST['nama'];
    $nim = $_POST['nim'];
    $iq = (float)$_POST['iq'];
    $eq = (float)$_POST['eq'];
    $attitude = (float)$_POST['attitude'];
    $association = (float)$_POST['association'];
    $friendship = (float)$_POST['friendship'];
    $effort_regulation = (float)$_POST['effort_regulation'];

    // Update the database
    $sql = "UPDATE mahasiswa SET 
            nama = ?, 
            nim = ?, 
            iq = ?, 
            eq = ?, 
            attitude = ?, 
            association = ?, 
            friendship = ?, 
            effort_regulation = ?
            WHERE id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssddddddi", 
        $nama, 
        $nim, 
        $iq, 
        $eq, 
        $attitude, 
        $association, 
        $friendship, 
        $effort_regulation, 
        $id
    );

    if ($stmt->execute()) {
        header('Location: ranking.php?success=updated');
        exit();
    } else {
        $error = "Gagal memperbarui data: " . $conn->error;
    }
}
?>

<?php include '../includes/header.php'; ?>

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    <i class="fas fa-edit text-indigo-600 mr-2"></i>Edit Data Mahasiswa
                </h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    Perbarui data mahasiswa di bawah ini.
                </p>
            </div>
            
            <div class="px-4 py-5 sm:p-6">
                <?php if (isset($error)): ?>
                    <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-circle text-red-400"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-700"><?php echo $error; ?></p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <form action="edit.php?id=<?php echo $id; ?>" method="POST" class="space-y-6">
                    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <div class="sm:col-span-3">
                            <label for="nama" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                            <input type="text" name="nama" id="nama" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                value="<?php echo htmlspecialchars($student['nama']); ?>">
                        </div>

                        <div class="sm:col-span-3">
                            <label for="nim" class="block text-sm font-medium text-gray-700">NIM</label>
                            <input type="text" name="nim" id="nim" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                value="<?php echo htmlspecialchars($student['nim']); ?>">
                        </div>

                        <div class="sm:col-span-2">
                            <label for="iq" class="block text-sm font-medium text-gray-700">IQ</label>
                            <input type="number" name="iq" id="iq" min="0" max="100" step="0.01" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                value="<?php echo htmlspecialchars($student['iq']); ?>">
                        </div>

                        <div class="sm:col-span-2">
                            <label for="eq" class="block text-sm font-medium text-gray-700">EQ</label>
                            <input type="number" name="eq" id="eq" min="0" max="100" step="0.01" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                value="<?php echo htmlspecialchars($student['eq']); ?>">
                        </div>

                        <div class="sm:col-span-2">
                            <label for="attitude" class="block text-sm font-medium text-gray-700">Attitude</label>
                            <input type="number" name="attitude" id="attitude" min="0" max="100" step="0.01" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                value="<?php echo htmlspecialchars($student['attitude']); ?>">
                        </div>

                        <div class="sm:col-span-2">
                            <label for="association" class="block text-sm font-medium text-gray-700">Association</label>
                            <input type="number" name="association" id="association" min="0" max="100" step="0.01" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                value="<?php echo htmlspecialchars($student['association']); ?>">
                        </div>

                        <div class="sm:col-span-2">
                            <label for="friendship" class="block text-sm font-medium text-gray-700">Friendship</label>
                            <input type="number" name="friendship" id="friendship" min="0" max="100" step="0.01" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                value="<?php echo htmlspecialchars($student['friendship']); ?>">
                        </div>

                        <div class="sm:col-span-2">
                            <label for="effort_regulation" class="block text-sm font-medium text-gray-700">Effort Regulation</label>
                            <input type="number" name="effort_regulation" id="effort_regulation" min="0" max="100" step="0.01" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                value="<?php echo htmlspecialchars($student['effort_regulation']); ?>">
                        </div>
                    </div>

                    <div class="pt-5">
                        <div class="flex justify-end space-x-3">
                            <a href="ranking.php" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Batal
                            </a>
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <i class="fas fa-save mr-2"></i>Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
