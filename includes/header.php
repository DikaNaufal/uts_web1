<?php
// includes/header.php
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>VOC ASLI BANDUNG</title>
  <!-- Tailwind CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800">
<header class="bg-blue-600 text-white">
  <div class="container mx-auto px-4 py-4 flex justify-between items-center">
    <a href="index.php" class="text-xl font-semibold">VOC ASLI BANDUNG</a>
    <nav>
      <a class="mr-4" href="index.php">Beranda</a>
      <?php if(!isset($_SESSION['user_id'])): ?>
        <a class="mr-4" href="login.php">Login</a>
        <a href="register.php">Daftar</a>
      <?php else: ?>
        <a class="mr-4" href="dashboard.php">Menu Utama</a>
        <a href="logout.php">Logout</a>
      <?php endif; ?>
    </nav>
  </div>
</header>
<main class="container mx-auto px-4 py-8">
