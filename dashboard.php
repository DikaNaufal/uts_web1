<?php
require 'includes/db.php';
require 'includes/header.php';

if(!isset($_SESSION['user_id'])){
  header("Location: login.php");
  exit;
}
?>

<h1 class="text-2xl font-bold">Menu Utama</h1>
<p class="mb-4">Selamat datang, <?=htmlspecialchars($_SESSION['user_name'])?>!</p>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
  <div class="bg-white rounded p-4 shadow">
    <h3 class="font-semibold">Profil</h3>
    <p class="text-sm text-gray-600">Nama: <?=htmlspecialchars($_SESSION['user_name'])?></p>
    <p class="text-sm text-gray-600">ID: <?=htmlspecialchars($_SESSION['user_id'])?></p>
  </div>

  <div class="bg-white rounded p-4 shadow">
    <h3 class="font-semibold">Kelola Informasi</h3>
    <p class="text-sm text-gray-600">Untuk mengelola informasi via API gunakan endpoint di <code>/api_v1/infos.php</code></p>
  </div>
</div>

<?php require 'includes/footer.php'; ?>
