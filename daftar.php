<?php require_once __DIR__.'/config.php'; /* public registration */ $user=current_user();
$info=''; $err='';

// Ensure new columns exist (SQLite) — safe no-op for MySQL if used
try{
  $driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
  if ($driver==='sqlite'){
    $cols = $pdo->query("PRAGMA table_info('registrations')")->fetchAll();
    $cmap = array_column($cols,'name');
    $add = function($name,$type='TEXT') use (&$pdo,$cmap){ if(!in_array($name,$cmap)) $pdo->exec("ALTER TABLE registrations ADD COLUMN $name $type"); };
    $add('name','TEXT');
    $add('email','TEXT');
    $add('affiliation','TEXT');
    $add('origin','TEXT');
    $add('whatsapp','TEXT');
    $add('participation','TEXT');
  }
} catch (Exception $e) { /* ignore */ }

if($_SERVER['REQUEST_METHOD']==='POST'){
  $name = trim($_POST['name']??($user['name']??''));
  $email = trim($_POST['email']??($user['email']??''));
  $aff  = trim($_POST['affiliation']??'');
  $origin = trim($_POST['origin']??''); // internal|external
  $wa   = preg_replace('/\D+/','', $_POST['whatsapp']??'');
  $part = trim($_POST['participation']??'Seminar'); // Seminar|Call for Paper|Keduanya
  if(!$name||!$email){ $err='Nama dan Email wajib diisi.'; }
  else{
    $st=$pdo->prepare('INSERT INTO registrations(user_id, ticket_type, payment_status, name, email, affiliation, origin, whatsapp, participation) VALUES (?,?,?,?,?,?,?,?,?)');
    $st->execute([ $user['id'] ?? null, 'Reguler','unpaid',$name,$email,$aff,$origin,$wa,$part ]);
    $info='Pendaftaran berhasil disimpan.';
  }
}
?>
<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Daftar Seminar</title><link rel="stylesheet" href="assets/css/style.css"></head>
<body>
<?php include __DIR__ . '/partials/header.php'; ?>
<div class="container">
<h2>Daftar Seminar</h2>
<?php if($err): ?><div class="card"><div class="p" style="color:#c00"><?php echo e($err); ?></div></div><?php endif; ?>
<?php if($info): ?><div class="card"><div class="p" style="color:#070"><?php echo e($info); ?></div></div><?php endif; ?>
<form method="post">
  <label>Nama Lengkap</label>
  <input name="name" value="<?php echo e($user['name']??''); ?>" required>

  <label>Asal Institusi</label>
  <input name="affiliation" placeholder="Nama institusi / instansi">

  <label>Status Peserta</label>
  <select name="origin">
    <option value="">-- Pilih --</option>
    <option value="internal">Internal UNW</option>
    <option value="external">External</option>
  </select>

  <label>Alamat Email</label>
  <input type="email" name="email" value="<?php echo e($user['email']??''); ?>" required>

  <label>Nomor WhatsApp</label>
  <input name="whatsapp" placeholder="Contoh: 62812xxxxxxx">

  <label>Mengikuti</label>
  <select name="participation">
    <option>Seminar</option>
    <option>Call for Paper</option>
    <option>Keduanya</option>
  </select>

  <button class="btn" type="submit">Simpan Pendaftaran</button>
</form>
</div>
<?php include __DIR__ . '/partials/footer.php'; ?>
</body></html>
