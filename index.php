<?php
require_once __DIR__ . "/konfigurasi/koneksi.php";

if (isset($_SESSION['pengguna'])) {
    header("Location: beranda/index.php");
} else {
    header("Location: autentikasi/login.php");
}
exit;
