<?php require_once __DIR__.'/config.php';
if (is_logged_in()) { redirect('index.php'); }
$err='';
if($_SERVER['REQUEST_METHOD']==='POST'){
  $name=trim($_POST['name']??'');
  $email=trim($_POST['email']??'');
  $aff=trim($_POST['affiliation']??'');
  $pass=$_POST['password']??'';
  if(!$name||!$email||strlen($pass)<6){ $err='Lengkapi data dan gunakan sandi minimal 6 karakter.'; }
  else{
    try{
      $hash=password_hash($pass,PASSWORD_DEFAULT);
      $st=$pdo->prepare('INSERT INTO users(name,email,affiliation,password_hash,role) VALUES (?,?,?,?,?)');
      $st->execute([$name,$email,$aff,$hash,'peserta']);
      flash('msg','Akun berhasil dibuat. Silakan login.');
      redirect('login.php');
    }catch(Exception $e){ $err='Email sudah terdaftar atau tidak valid.'; }
  }
}
?>
<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Daftar</title><link rel="stylesheet" href="assets/css/style.css"></head>
<body>
<header><div class="navbar"><div class="brand">Seminar & CFP</div><nav><a href="index.php">Beranda</a></nav></div></header>
<div class="container">
<h2>Daftar Peserta</h2>
<?php if($err): ?><div class="card"><div class="p" style="color:#c00"><?php echo e($err); ?></div></div><?php endif; ?>
<form method="post">
  <label>Nama Lengkap</label>
  <input name="name" required>
  <label>Email</label>
  <input type="email" name="email" required>
  <label>Institusi/Afiliasi</label>
  <input name="affiliation">
  <label>Kata Sandi</label>
  <input type="password" name="password" required>
  <button class="btn" type="submit">Buat Akun</button>
</form>
</div>
</body></html>
