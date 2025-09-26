<?php
// helpers.php
function e($str) { return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8'); }
function redirect($url) { header('Location: ' . $url); exit; }
function is_logged_in() { return isset($_SESSION['user']); }
function current_user() { return $_SESSION['user'] ?? null; }
function is_admin() { return isset($_SESSION['user']) && ($_SESSION['user']['role'] ?? '') === 'admin'; }
function require_login() { if (!is_logged_in()) { redirect('login.php'); } }
function require_admin() { if (!is_admin()) { redirect('../login.php'); } }

function flash($key, $msg = null) {
    if ($msg !== null) { $_SESSION['flash'][$key] = $msg; return; }
    if (isset($_SESSION['flash'][$key])) { $m = $_SESSION['flash'][$key]; unset($_SESSION['flash'][$key]); return $m; }
    return null;
}
?>