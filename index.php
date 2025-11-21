<?php
require 'includes/db.php';
require 'includes/header.php';

// ambil semua infos
$stmt = $pdo->query("SELECT id, title, summary, image, created_at FROM infos ORDER BY created_at DESC");
$infos = $stmt->fetchAll();
?>
<h1 class="text-3xl font-bold mb-6">Informasi Perusahaan</h1>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
  <?php foreach($infos as $i): ?>
    <div class="bg-white rounded shadow p-4">
      <?php if($i['image']): ?>
        <img src="<?=htmlspecialchars($i['image'])?>" alt="" class="w-full h-40 object-cover rounded mb-3">
      <?php endif; ?>
      <h2 class="text-xl font-semibold"><?=htmlspecialchars($i['title'])?></h2>
      <p class="text-sm text-gray-600 mb-3"><?=htmlspecialchars($i['summary'])?></p>
      <a class="inline-block bg-blue-600 text-white px-3 py-1 rounded" href="info.php?id=<?= $i['id'] ?>">Baca Detail</a>
    </div>
  <?php endforeach; ?>
</div>

<?php require 'includes/footer.php'; ?>
