<?php
require 'includes/db.php';
require 'includes/header.php';

$errors = [];
$success = '';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
  $name = trim($_POST['name'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $password = $_POST['password'] ?? '';
  $cpassword = $_POST['cpassword'] ?? '';

  if($name === '' || $email === '' || $password === '') {
    $errors[] = "Semua field harus diisi.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)){
    $errors[] = "Email tidak valid.";
  } elseif ($password !== $cpassword){
    $errors[] = "Password dan konfirmasi tidak cocok.";
  } else {
    // cek email
    $st = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $st->execute([$email]);
    if($st->fetch()){
      $errors[] = "Email sudah terdaftar.";
    } else {
      $hash = password_hash($password, PASSWORD_DEFAULT);
      $ins = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
      $ins->execute([$name, $email, $hash]);
      $success = "Registrasi berhasil. <a href='login.php' class='text-blue-600'>Login</a>";
    }
  }
}
?>

<div class="max-w-md mx-auto bg-white p-6 rounded shadow">
  <h2 class="text-xl font-semibold mb-4">Registrasi</h2>

  <?php if($success): ?>
    <div class="bg-green-100 p-3 rounded mb-3 text-green-800"><?= $success ?></div>
  <?php endif; ?>

  <?php if($errors): ?>
    <div class="bg-red-50 p-3 rounded mb-3 text-red-700">
      <ul>
        <?php foreach($errors as $e) echo "<li>".htmlspecialchars($e)."</li>"; ?>
      </ul>
    </div>
  <?php endif; ?>

  <form method="post" class="space-y-3">
    <input name="name" placeholder="Nama" class="w-full border rounded px-3 py-2">
    <input name="email" placeholder="Email" class="w-full border rounded px-3 py-2" type="email">
    <input name="password" placeholder="Password" class="w-full border rounded px-3 py-2" type="password">
    <input name="cpassword" placeholder="Konfirmasi Password" class="w-full border rounded px-3 py-2" type="password">
    <button class="w-full bg-blue-600 text-white py-2 rounded">Daftar</button>
  </form>
</div>

<?php require 'includes/footer.php'; ?>
