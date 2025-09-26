<?php
// init_db.php - create tables if not exists and seed admin

$pdo->exec('CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT,
    email TEXT UNIQUE,
    affiliation TEXT,
    password_hash TEXT NOT NULL,
    role TEXT NOT NULL DEFAULT "peserta",
    created_at TEXT DEFAULT CURRENT_TIMESTAMP
)');

$pdo->exec('CREATE TABLE IF NOT EXISTS banners (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT,
    subtitle TEXT,
    image_path TEXT,
    is_active INTEGER DEFAULT 1,
    sort_order INTEGER DEFAULT 0
)');

$pdo->exec('CREATE TABLE IF NOT EXISTS speakers (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT,
    title TEXT,
    institution TEXT,
    bio TEXT,
    photo_path TEXT,
    order_no INTEGER DEFAULT 0
)');

$pdo->exec('CREATE TABLE IF NOT EXISTS articles (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER,
    title TEXT,
    abstract TEXT,
    keywords TEXT,
    file_path TEXT,
    status TEXT DEFAULT "submitted",
    created_at TEXT DEFAULT CURRENT_TIMESTAMP
)');

$pdo->exec('CREATE TABLE IF NOT EXISTS registrations (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER,
    ticket_type TEXT,
    payment_status TEXT DEFAULT "unpaid",
    created_at TEXT DEFAULT CURRENT_TIMESTAMP
)');

// seed default speakers if table empty (3 slots)
$cnt = (int)$pdo->query('SELECT COUNT(*) AS c FROM speakers')->fetch()['c'];
if ($cnt < 3) {
    for ($i = $cnt; $i < 3; $i++) {
        $stmt = $pdo->prepare('INSERT INTO speakers(name,title,institution,bio,photo_path,order_no) VALUES (?,?,?,?,?,?)');
        $stmt->execute([
            'Narasumber ' . ($i+1), 'Jabatan', 'Institusi', 'Deskripsi singkat narasumber.', 'assets/img_speaker_placeholder.png', $i+1
        ]);
    }
}

// seed sample banner if none
$cntB = (int)$pdo->query('SELECT COUNT(*) AS c FROM banners')->fetch()['c'];
if ($cntB == 0) {
    $stmt = $pdo->prepare('INSERT INTO banners(title, subtitle, image_path, is_active, sort_order) VALUES (?,?,?,?,?)');
    $stmt->execute([
        'Seminar & Call for Paper 2025', 'Tema: Inovasi dan Kolaborasi', 'assets/sample_banner.jpg', 1, 1
    ]);
}

// seed admin if not exists (email admin@example.com / password: admineko)
$adminEmail = 'admin@example.com';
$exists = $pdo->prepare('SELECT id FROM users WHERE email=?');
$exists->execute([$adminEmail]);
if (!$exists->fetch()) {
    // create hash using PHP's password_hash
    $hash = password_hash('admineko', PASSWORD_DEFAULT);
    $ins = $pdo->prepare('INSERT INTO users(name,email,affiliation,password_hash,role) VALUES (?,?,?,?,?)');
    $ins->execute(['Administrator', $adminEmail, 'Panitia', $hash, 'admin']);
}
?>