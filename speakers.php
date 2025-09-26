<?php require_once __DIR__.'/config.php';
$rows=$pdo->query('SELECT * FROM speakers ORDER BY order_no ASC, id ASC')->fetchAll();
?>
<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Narasumber</title><link rel="stylesheet" href="assets/css/style.css"></head>
<body>
<header>
  <div class="navbar">
    <div class="brand">Seminar & CFP</div>
    <nav>
      <a href="index.php">Beranda</a>
      <a href="daftar.php">Daftar Seminar</a>
      <a href="upload.php">Unggah Artikel</a>
      <a href="speakers.php">Narasumber</a>
      <?php if(is_logged_in()): ?>
        <?php if(is_admin()): ?><a href="admin/">Dashboard Admin</a><?php endif; ?>
        <a href="logout.php">Logout</a>
      <?php else: ?>
        <a class="btn" href="login.php">Login</a>
      <?php endif; ?>
    </nav>
  </div>
</header>
<div class="container">
  <h2>Daftar Narasumber</h2>
  <div class="grid3">
    <?php foreach($rows as $s): ?>
      <div class="card">
        <img src="<?php echo e($s['photo_path'] ?: 'assets/img_speaker_placeholder.png'); ?>" alt="narasumber">
        <div class="p">
          <h3><?php echo e($s['name']); ?></h3>
          <div class="muted"><?php echo e($s['title']); ?><?php echo $s['institution']? ', '.e($s['institution']) : ''; ?></div>
          <?php if($s['bio']): ?><p><?php echo e($s['bio']); ?></p><?php endif; ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>
</body></html>
