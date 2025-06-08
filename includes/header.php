<?php
// Determine the current page for active state highlighting
$current_page = basename($_SERVER['PHP_SELF']);
$is_form_page = ($current_page === 'form.php');
$is_ranking_page = ($current_page === 'ranking.php');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemilih Mahasiswa Terbaik</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
        }
        .card {
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }
        .card:hover {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navbar -->
    <nav class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <i class="fas fa-graduation-cap text-indigo-600 text-2xl mr-2"></i>
                        <span class="text-xl font-semibold text-gray-800">Pemilih Mahasiswa Terbaik</span>
                    </div>
                    <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                        <a href="form.php" class="<?php echo $is_form_page ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'; ?> inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            <i class="fas fa-edit mr-2"></i>Formulir
                        </a>
                        <a href="ranking.php" class="<?php echo $is_ranking_page ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'; ?> inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            <i class="fas fa-trophy mr-2"></i>Peringkat
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Mobile menu -->
    <div class="sm:hidden bg-white shadow-md">
        <div class="flex justify-around py-2">
            <a href="form.php" class="flex flex-col items-center <?php echo $is_form_page ? 'text-indigo-600' : 'text-gray-500 hover:text-indigo-600'; ?> px-3 py-2 text-sm font-medium">
                <i class="fas fa-edit text-lg mb-1"></i>
                <span class="text-xs">Formulir</span>
            </a>
            <a href="ranking.php" class="flex flex-col items-center <?php echo $is_ranking_page ? 'text-indigo-600' : 'text-gray-500 hover:text-indigo-600'; ?> px-3 py-2 text-sm font-medium">
                <i class="fas fa-trophy text-lg mb-1"></i>
                <span class="text-xs">Peringkat</span>
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
