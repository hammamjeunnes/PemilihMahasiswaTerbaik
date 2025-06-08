<?php
require_once __DIR__ . '/../config/database.php';

// Check if ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: ranking.php?error=noid');
    exit();
}

$id = $_GET['id'];
$conn = getConnection();

// Check if student exists
$sql = "SELECT id FROM mahasiswa WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: ranking.php?error=notfound');
    exit();
}

// Delete the student
$sql = "DELETE FROM mahasiswa WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header('Location: ranking.php?success=deleted');
} else {
    header('Location: ranking.php?error=deletefailed');
}

exit();
?>
