<?php
// konfigurasi/koneksi.php

$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_samsat";

$koneksi = new mysqli($host, $user, $pass, $db);
if ($koneksi->connect_error) {
    die("Koneksi database gagal: " . $koneksi->connect_error);
}
$koneksi->set_charset("utf8mb4");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function wajib_login()
{
    if (!isset($_SESSION['pengguna'])) {
        header("Location: /samsat/autentikasi/login.php");
        exit;
    }
}

function pengguna()
{
    return $_SESSION['pengguna'] ?? null;
}

function e($str)
{
    return htmlspecialchars((string)$str, ENT_QUOTES, 'UTF-8');
}
