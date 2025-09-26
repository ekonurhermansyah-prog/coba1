<?php
// config.php
session_start();

// ---- DATABASE CONFIG ----
// Default: SQLite (no setup). To switch to MySQL, see notes below.
$DB_DRIVER = getenv('DB_DRIVER') ?: 'sqlite';

try {
    if ($DB_DRIVER === 'mysql') {
        $DB_HOST = getenv('DB_HOST') ?: 'localhost';
        $DB_NAME = getenv('DB_NAME') ?: 'seminar_db';
        $DB_USER = getenv('DB_USER') ?: 'root';
        $DB_PASS = getenv('DB_PASS') ?: '';
        $dsn = "mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8mb4";
        $pdo = new PDO($dsn, $DB_USER, $DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    } else {
        $dbPath = __DIR__ . '/database.sqlite';
        $dsn = 'sqlite:' . $dbPath;
        $pdo = new PDO($dsn);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }
} catch (Exception $e) {
    die('Database connection failed: ' . $e->getMessage());
}

function base_url() {
    // Auto-detect base URL
    $proto = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $path = rtrim(str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']), '/');
    return $proto . '://' . $host . $path;
}

require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/init_db.php';
?>