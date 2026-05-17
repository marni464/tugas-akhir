<?php
require_once "../konfigurasi/koneksi.php";
session_destroy();
header("Location: login.php");
