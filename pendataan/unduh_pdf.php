<?php
require_once "../konfigurasi/koneksi.php";
wajib_login();

require_once "../vendor/autoload.php";

use Dompdf\Dompdf;

$q = trim($_GET['q'] ?? '');
$jenis_kendaraan = $_GET['jenis_kendaraan'] ?? '';
$jenis_plat = $_GET['jenis_plat'] ?? '';
$jenis_layanan = $_GET['jenis_layanan'] ?? '';
$whereParts = [];
$params = [];
$types = "";

if ($q !== "") {
    $whereParts[] = "(nopol LIKE ? OR pemilik LIKE ? OR jenis_layanan LIKE ?)";
    $like = "%$q%";
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
    $types .= "sss";
}

if ($jenis_kendaraan !== "") {
    $whereParts[] = "jenis_kendaraan = ?";
    $types .= "s";
    $params[] = $jenis_kendaraan;
}

if ($jenis_plat !== "") {
    $whereParts[] = "jenis_plat = ?";
    $types .= "s";
    $params[] = $jenis_plat;
}

if ($jenis_layanan !== "") {
    $whereParts[] = "jenis_layanan = ?";
    $types .= "s";
    $params[] = $jenis_layanan;
}

$where = $whereParts ? ("WHERE " . implode(" AND ", $whereParts)) : "";

$stmt = $koneksi->prepare("SELECT * FROM registrasi_stnk $where ORDER BY tanggal DESC, id DESC");
if ($types) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$html = '
<h2 style="text-align:center;margin:0;">Laporan Pendataan Registrasi STNK</h2>
<p style="text-align:center;margin:6px 0 14px 0;">SAMSAT Palopo</p>
<table width="100%" border="1" cellspacing="0" cellpadding="6" style="border-collapse:collapse;font-size:12px;">
  <thead>
    <tr style="background:#eee;">
      <th>Tanggal</th>
      <th>Nopol</th>
      <th>Jenis Kendaraan</th>
      <th>Jenis Plat</th>
      <th>Jenis Layanan</th>
      <th>Pemilik</th>
    </tr>
  </thead>
  <tbody>
';

foreach ($rows as $r) {
    $html .= '<tr>
    <td>' . e($r['tanggal']) . '</td>
    <td>' . e($r['nopol']) . '</td>
    <td>' . e($r['jenis_kendaraan']) . '</td>
    <td>' . e($r['jenis_plat']) . '</td>
    <td>' . e($r['jenis_layanan']) . '</td>
    <td>' . e($r['pemilik']) . '</td>
  </tr>';
}

$html .= '</tbody></table>';

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream("laporan_pendataan_stnk.pdf", ["Attachment" => true]);
