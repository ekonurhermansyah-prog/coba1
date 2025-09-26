<?php require_once __DIR__.'/config.php';
if (is_logged_in()) { if (is_admin()) redirect('admin/'); else redirect('index.php'); }

$error = '';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $stmt = $pdo->prepare('SELECT * FROM users WHERE email=?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user'] = $user;
        if ($user['role']==='admin') redirect('admin/');
        redirect('index.php');
    } else {
        $error = 'Email atau kata sandi salah.';
    }
}
?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Login</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<header><div class="navbar"><div class="brand">Seminar & CFP</div><nav><a href="index.php">Beranda</a></nav></div></header>
<div class="container">
  <h2>Login</h2>
  <?php if($msg = flash('msg')): ?><div class="card"><div class="p"><?php echo e($msg); ?></div></div><?php endif; ?>
  <?php if($error): ?><div class="card"><div class="p" style="color:#c00"><?php echo e($error); ?></div></div><?php endif; ?>
  <form method="post">
    <label>Email</label>
    <input type="email" name="email" required>
    <label>Kata Sandi</label>
    <input type="password" name="password" required>
    <button class="btn" type="submit">Masuk</button>
  </form>
  <p>Belum punya akun? <a href="register.php">Daftar sebagai Peserta</a></p>
</div>
</body></html>
