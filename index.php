<?php require_once __DIR__.'/config.php'; ?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Seminar & Call for Paper</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<header>
  <div class="navbar">
    <div class="brand">Seminar & CFP</div>
    <nav>
      <a href="index.php">Beranda</a>
      <a href="daftar.php">Daftar Kegiatan</a>
      <a href="https://callforpaper.unw.ac.id/index.php/ICOELH">Unggah Artikel</a>
      <a href="speakers.php">Narasumber</a>
      <a href="https://docs.google.com/document/d/1I3gVI4TBMYBGt8yuXBdOQ8i2M_37tiIe/edit?rtpof=true&sd=true&tab=t.0">Template Artikel</a>
      <?php if(is_logged_in()): ?>
        <?php if(is_admin()): ?>
          <a href="admin/">Dashboard Admin</a>
        <?php endif; ?>
        <a href="logout.php">Logout</a>
      <?php else: ?>
        <a class="btn" href="login.php">Login</a>
      <?php endif; ?>
    </nav>
  </div>
</header>

<?php
$banners = $pdo->query('SELECT * FROM banners WHERE is_active=1 ORDER BY sort_order ASC,id ASC')->fetchAll();
?>
<section class="slider">
  <?php foreach($banners as $idx=>$b): ?>
    <div class="slide <?php echo $idx===0?'active':''; ?>">
      <img src="<?php echo e($b['image_path']); ?>" alt="banner">
      <div class="caption">
        <h2><?php echo e($b['title']); ?></h2>
        <p><?php echo e($b['subtitle']); ?></p>
        <div class=\"cta-group\">
          <a class=\"btn\" href=\"daftar.php\">Daftar Kegiatan</a>
          <a class=\"btn secondary\" href=\"https://callforpaper.unw.ac.id/index.php/ICOELH\">Unggah Artikel</a>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
  <div class="dots">
    <?php foreach($banners as $idx=>$b): ?><div class="dot <?php echo $idx===0?'active':''; ?>"></div><?php endforeach; ?>
  </div>
</section>

<main class="container">
  <section>
    <h2>Tentang Kegiatan</h2>
    <p>Selamat datang di <strong>Seminar & Call for Paper</strong>. Silakan mendaftar sebagai peserta, mengunggah artikel, dan unduh template artikel.
    Banner slider di halaman ini dapat diedit dari Dashboard Admin.</p>
  </section>

  <section>
    <h2>Narasumber Utama</h2>
    <div class="grid3">
      <?php $speakers = $pdo->query('SELECT * FROM speakers ORDER BY order_no ASC, id ASC LIMIT 6')->fetchAll(); ?>
      <?php foreach($speakers as $s): ?>
        <div class="card">
          <img src="<?php echo e($s['photo_path']); ?>" alt="narasumber">
          <div class="p">
            <h3><?php echo e($s['name']); ?></h3>
            <div class="muted"><?php echo e($s['title']); ?>, <?php echo e($s['institution']); ?></div>
            <p><?php echo e($s['bio']); ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </section>
</main>

<footer>
  <div class="footer-inner">
    <div>
      <h3>Seminar & Call for Paper</h3>
      <p>Diselenggarakan oleh Panitia Seminar. Hubungi kami untuk kemitraan dan informasi lebih lanjut.</p>
      <p>Email: panitia@example.com<br>Telepon: +62-812-0000-0000</p>
    </div>
    <div>
      <h4>Tautan</h4>
      <ul>
        <li><a href="daftar.php">Daftar Kegiatan</a></li>
        <li><a href="https://callforpaper.unw.ac.id/index.php/ICOELH">Unggah Artikel</a>
      <a href="speakers.php">Narasumber</a></li>
        <li><a href="https://docs.google.com/document/d/1I3gVI4TBMYBGt8yuXBdOQ8i2M_37tiIe/edit?rtpof=true&sd=true&tab=t.0">Template Artikel</a></li>
      </ul>
    </div>
    <div>
      <h4>Ikuti Kami</h4>
      <p>
        <a href="#">Instagram</a> · <a href="#">YouTube</a> · <a href="#">LinkedIn</a>
      </p>
      <p>Alamat: Kampus XYZ, Indonesia</p>
    </div>
  </div>
  <div class="copy">© <?php echo date('Y'); ?> Seminar & CFP — Dibuat dengan ❤</div>
</footer>

<script src="assets/js/slider.js"></script>
</body>
</html>
