<?php
require 'includes/db.php';
require 'includes/header.php';

$errors = [];
if($_SERVER['REQUEST_METHOD'] === 'POST'){
  $email = trim($_POST['email'] ?? '');
  $password = $_POST['password'] ?? '';

  // server-side basic check
  if($email === '' || $password === '') {
    $errors[] = "Email & password wajib diisi.";
  } else {
    $st = $pdo->prepare("SELECT id, name, password FROM users WHERE email = ?");
    $st->execute([$email]);
    $user = $st->fetch();
    if($user && password_verify($password, $user['password'])){
      // login sukses
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['user_name'] = $user['name'];
      header("Location: dashboard.php");
      exit;
    } else {
      $errors[] = "Kombinasi email/password salah.";
    }
  }
}
?>

<div class="max-w-md mx-auto bg-white p-6 rounded shadow">
  <h2 class="text-xl font-semibold mb-4">Login</h2>

  <?php if($errors): ?>
    <div class="bg-red-50 p-3 rounded mb-3 text-red-700">
      <ul><?php foreach($errors as $e) echo "<li>".htmlspecialchars($e)."</li>"; ?></ul>
    </div>
  <?php endif; ?>

  <form method="post" onsubmit="return validateLogin();" class="space-y-3">
    <input id="email" name="email" placeholder="Email" class="w-full border rounded px-3 py-2" type="email">
    <input id="password" name="password" placeholder="Password" class="w-full border rounded px-3 py-2" type="password">
    <button class="w-full bg-blue-600 text-white py-2 rounded">Login</button>
  </form>
</div>

<?php require 'includes/footer.php'; ?>
