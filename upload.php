<?php require_once __DIR__.'/config.php'; require_login(); $user=current_user();
$err='';$ok='';
if($_SERVER['REQUEST_METHOD']==='POST'){
  $title=trim($_POST['title']??'');
  $abstrak=trim($_POST['abstract']??'');
  $keywords=trim($_POST['keywords']??'');
  if(!$title||!isset($_FILES['file'])){ $err='Judul dan berkas wajib.'; }
  else{
    $f=$_FILES['file'];
    if($f['error']!==UPLOAD_ERR_OK){ $err='Gagal mengunggah berkas.'; }
    else{
      $ext=strtolower(pathinfo($f['name'], PATHINFO_EXTENSION));
      if(!in_array($ext,['pdf','docx'])){ $err='Format harus PDF atau DOCX.'; }
      else{
        $dir='uploads/artikel/'; if(!is_dir($dir)) mkdir($dir,0777,true);
        $new= $dir . time() . '_' . preg_replace('/[^a-z0-9\._-]/i','_', $f['name']);
        if(move_uploaded_file($f['tmp_name'],$new)){
          $st=$pdo->prepare('INSERT INTO articles(user_id,title,abstract,keywords,file_path) VALUES (?,?,?,?,?)');
          $st->execute([$user['id'],$title,$abstrak,$keywords,$new]);
          $ok='Artikel berhasil diunggah.';
        }else{$err='Tidak bisa menyimpan berkas.';}
      }
    }
  }
}
?>
<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Unggah Artikel</title><link rel="stylesheet" href="assets/css/style.css"></head>
<body>
<header><div class="navbar"><div class="brand">Seminar & CFP</div>
<nav><a href="index.php">Beranda</a><a href="daftar.php">Daftar Seminar</a><?php if(is_admin()):?><a href="admin/">Dashboard Admin</a><?php endif;?><a href="logout.php">Logout</a></nav></div></header>
<div class="container">
<h2>Unggah Artikel</h2>
<?php if($err): ?><div class="card"><div class="p" style="color:#c00"><?php echo e($err); ?></div></div><?php endif; ?>
<?php if($ok): ?><div class="card"><div class="p" style="color:#0a0"><?php echo e($ok); ?></div></div><?php endif; ?>
<form method="post" enctype="multipart/form-data">
  <label>Judul</label>
  <input name="title" required>
  <label>Abstrak</label>
  <textarea name="abstract" rows="5" required></textarea>
  <label>Kata Kunci (pisahkan dengan koma)</label>
  <input name="keywords">
  <label>Berkas (PDF atau DOCX)</label>
  <input type="file" name="file" accept=".pdf,.docx" required>
  <button class="btn" type="submit">Unggah</button>
</form>
<p>Butuh template? Unduh <a class="btn secondary" href="https://docs.google.com/document/d/1I3gVI4TBMYBGt8yuXBdOQ8i2M_37tiIe/edit?rtpof=true&sd=true&tab=t.0">Template Artikel</a></p>
</div></body></html>
