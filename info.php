<?php
require 'includes/db.php';
require 'includes/header.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if($id <= 0){
  echo "<div class='text-red-600'>Informasi tidak valid.</div>";
  require 'includes/footer.php';
  exit;
}

$stmt = $pdo->prepare("SELECT * FROM infos WHERE id = ?");
$stmt->execute([$id]);
$info = $stmt->fetch();

if(!$info){
  echo "<div class='text-red-600'>Informasi tidak ditemukan.</div>";
  require 'includes/footer.php';
  exit;
}
?>

<article class="bg-white rounded shadow p-6">
  <h1 class="text-2xl font-bold mb-2"><?=htmlspecialchars($info['title'])?></h1>
  <p class="text-sm text-gray-500 mb-4"><?=date('d M Y', strtotime($info['created_at']))?></p>
  <?php if($info['image']): ?>
    <img src="<?=htmlspecialchars($info['image'])?>" alt="" class="w-full h-64 object-cover rounded mb-4">
  <?php endif; ?>
  <div class="prose max-w-none">
    <?= nl2br(htmlspecialchars($info['content'])) ?>
  </div>
  <a class="inline-block mt-4 text-sm text-blue-600" href="index.php">&larr; Kembali</a>
</article>

<?php require 'includes/footer.php'; ?>
