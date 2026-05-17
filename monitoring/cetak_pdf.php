<?php
require_once "../konfigurasi/koneksi.php";
wajib_login();

require_once "../vendor/autoload.php";

use Dompdf\Dompdf;

// ambil dari POST (utama)
$gambar_grafik = $_POST['gambar_grafik'] ?? '';

$mulai = $_POST['mulai'] ?? '';
$sampai = $_POST['sampai'] ?? '';
$kendaraan = $_POST['kendaraan'] ?? '';
$plat = $_POST['plat'] ?? '';
$layanan = $_POST['layanan'] ?? '';

// jika ada yang akses via GET (opsional)
if (!$gambar_grafik) {
  $mulai = $_GET['mulai'] ?? $mulai;
  $sampai = $_GET['sampai'] ?? $sampai;
  $kendaraan = $_GET['kendaraan'] ?? $kendaraan;
  $plat = $_GET['plat'] ?? $plat;
  $layanan = $_GET['layanan'] ?? $layanan;
}

// susun info filter
$infoFilter = "Filter: ";
$infoFilter .= ($mulai && $sampai) ? "Periode $mulai s/d $sampai; " : "Semua Periode; ";
$infoFilter .= $kendaraan ? "Kendaraan $kendaraan; " : "Kendaraan Semua; ";
$infoFilter .= $plat ? "Plat $plat; " : "Plat Semua; ";
$infoFilter .= $layanan ? "Layanan $layanan; " : "Layanan Semua; ";

// ambil ringkasan data sesuai filter (biar PDF ada angka juga)
$where = [];
$params = [];
$types = "";

if ($mulai && $sampai) {
  $where[] = "tanggal BETWEEN ? AND ?";
  $types .= "ss";
  $params[] = $mulai;
  $params[] = $sampai;
}
if ($kendaraan) {
  $where[] = "jenis_kendaraan=?";
  $types .= "s";
  $params[] = $kendaraan;
}
if ($plat) {
  $where[] = "jenis_plat=?";
  $types .= "s";
  $params[] = $plat;
}
if ($layanan) {
  $where[] = "jenis_layanan=?";
  $types .= "s";
  $params[] = $layanan;
}

$wsql = $where ? ("WHERE " . implode(" AND ", $where)) : "";

$sql = "
SELECT DATE_FORMAT(tanggal, '%Y-%m') periode,
COUNT(*) total,
SUM(CASE WHEN jenis_plat='Merah' THEN 1 ELSE 0 END) merah,
SUM(CASE WHEN jenis_plat='Kuning' THEN 1 ELSE 0 END) kuning,
SUM(CASE WHEN jenis_plat='Putih' THEN 1 ELSE 0 END) putih
FROM registrasi_stnk
$wsql
GROUP BY periode
ORDER BY periode ASC
";

$stmt = $koneksi->prepare($sql);
if ($types) $stmt->bind_param($types, ...$params);
$stmt->execute();
$rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// cek gambar
$bagianGambar = '';
if ($gambar_grafik && str_starts_with($gambar_grafik, 'data:image/png;base64,')) {
  $bagianGambar = '
    <div style="text-align:center;margin-top:10px;">
      <img src="' . e($gambar_grafik) . '" style="width:100%; max-width:900px;">
    </div>
  ';
} else {
  $bagianGambar = '<p style="color:red;font-size:12px;">Grafik tidak terkirim. Silakan klik "Lihat" lalu "Cetak PDF".</p>';
}

// HTML PDF
$html = '
  <div style="text-align:center;">
    <h2 style="margin:0;">Laporan Monitoring Registrasi STNK</h2>
    <p style="margin:6px 0 0 0;">SAMSAT Palopo</p>
  </div>
  <p style="font-size:12px;margin:10px 0;">' . e($infoFilter) . '</p>

  ' . $bagianGambar . '

  <h4 style="margin:18px 0 8px 0;">Ringkasan Data (per Periode)</h4>
  <table width="100%" border="1" cellspacing="0" cellpadding="6" style="border-collapse:collapse;font-size:12px;">
    <thead>
      <tr style="background:#eee;">
        <th>Periode</th>
        <th>Total</th>
        <th>Merah</th>
        <th>Kuning</th>
        <th>Putih</th>
      </tr>
    </thead>
    <tbody>
';

if (!$rows) {
  $html .= '<tr><td colspan="5" style="text-align:center;">Tidak ada data</td></tr>';
} else {
  foreach ($rows as $r) {
    $html .= '<tr>
      <td>' . e($r['periode']) . '</td>
      <td>' . e($r['total']) . '</td>
      <td>' . e($r['merah']) . '</td>
      <td>' . e($r['kuning']) . '</td>
      <td>' . e($r['putih']) . '</td>
    </tr>';
  }
}

$html .= '
    </tbody>
  </table>

  <p style="font-size:10px;color:#666;margin-top:12px;">
    Dicetak pada: ' . date('Y-m-d H:i:s') . '
  </p>
';

// render PDF
$dompdf = new Dompdf([
  "isRemoteEnabled" => true,
  "isHtml5ParserEnabled" => true
]);

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream("laporan_monitoring_stnk_grafik.pdf", ["Attachment" => true]);
