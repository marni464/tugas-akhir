<?php
require_once "../konfigurasi/koneksi.php";
wajib_login();
header("Content-Type: application/json");

$mulai = $_GET['mulai'] ?? '';
$sampai = $_GET['sampai'] ?? '';
$kendaraan = $_GET['kendaraan'] ?? '';
$plat = $_GET['plat'] ?? '';
$layanan = $_GET['layanan'] ?? '';

$where = [];
$params = [];
$types = "";

// filter
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

// 1) Data grafik per periode
$sqlGrafik = "
SELECT DATE_FORMAT(tanggal, '%Y-%m') periode,
SUM(CASE WHEN jenis_plat='Merah' THEN 1 ELSE 0 END) merah,
SUM(CASE WHEN jenis_plat='Kuning' THEN 1 ELSE 0 END) kuning,
SUM(CASE WHEN jenis_plat='Putih' THEN 1 ELSE 0 END) putih
FROM registrasi_stnk
$wsql
GROUP BY periode
ORDER BY periode ASC
";

$stmt = $koneksi->prepare($sqlGrafik);
if ($types) $stmt->bind_param($types, ...$params);
$stmt->execute();
$res = $stmt->get_result();

$labels = [];
$merah = [];
$kuning = [];
$putih = [];
while ($r = $res->fetch_assoc()) {
    $labels[] = $r['periode'];
    $merah[] = (int)$r['merah'];
    $kuning[] = (int)$r['kuning'];
    $putih[] = (int)$r['putih'];
}

// Pastikan label titik plot berurutan, tidak lepas jika bulan tidak ada data
function bulanRange($awal, $akhir) {
    $cur = new DateTime($awal . '-01');
    $end = new DateTime($akhir . '-01');
    $end->modify('+1 month');
    $range = [];
    while ($cur < $end) {
        $range[] = $cur->format('Y-m');
        $cur->modify('+1 month');
    }
    return $range;
}

$startPeriode = '';
$endPeriode = '';
if ($mulai && $sampai) {
    $startPeriode = date('Y-m', strtotime($mulai));
    $endPeriode = date('Y-m', strtotime($sampai));
} else {
    $sqlMinMax = "SELECT MIN(tanggal) min_t, MAX(tanggal) max_t FROM registrasi_stnk $wsql";
    $stmtMinMax = $koneksi->prepare($sqlMinMax);
    if ($types) $stmtMinMax->bind_param($types, ...$params);
    $stmtMinMax->execute();
    $mm = $stmtMinMax->get_result()->fetch_assoc();
    if ($mm && $mm['min_t'] && $mm['max_t']) {
        $startPeriode = date('Y-m', strtotime($mm['min_t']));
        $endPeriode = date('Y-m', strtotime($mm['max_t']));
    }
}

if ($startPeriode && $endPeriode) {
    $allMonths = bulanRange($startPeriode, $endPeriode);
    $mapMerah = array_combine($labels, $merah ?: array_fill(0, count($labels),0));
    $mapKuning = array_combine($labels, $kuning ?: array_fill(0, count($labels),0));
    $mapPutih = array_combine($labels, $putih ?: array_fill(0, count($labels),0));

    $labels = [];
    $merah = [];
    $kuning = [];
    $putih = [];
    foreach ($allMonths as $m) {
        $labels[] = $m;
        $merah[] = isset($mapMerah[$m]) ? (int)$mapMerah[$m] : 0;
        $kuning[] = isset($mapKuning[$m]) ? (int)$mapKuning[$m] : 0;
        $putih[] = isset($mapPutih[$m]) ? (int)$mapPutih[$m] : 0;
    }
}

// 2) Ringkasan total
$sqlRingkasan = "
SELECT
COUNT(*) total,
SUM(CASE WHEN jenis_kendaraan='R2' THEN 1 ELSE 0 END) total_r2,
SUM(CASE WHEN jenis_kendaraan='R4' THEN 1 ELSE 0 END) total_r4,
SUM(CASE WHEN jenis_plat='Merah' THEN 1 ELSE 0 END) total_merah,
SUM(CASE WHEN jenis_plat='Kuning' THEN 1 ELSE 0 END) total_kuning,
SUM(CASE WHEN jenis_plat='Putih' THEN 1 ELSE 0 END) total_putih
FROM registrasi_stnk
$wsql
";

$stmt2 = $koneksi->prepare($sqlRingkasan);
if ($types) $stmt2->bind_param($types, ...$params);
$stmt2->execute();
$ringkas = $stmt2->get_result()->fetch_assoc() ?: [
    'total' => 0,
    'total_r2' => 0,
    'total_r4' => 0,
    'total_merah' => 0,
    'total_kuning' => 0,
    'total_putih' => 0
];

// output
echo json_encode([
    'labels' => $labels,
    'merah' => $merah,
    'kuning' => $kuning,
    'putih' => $putih,
    'ringkas' => [
        'total' => (int)$ringkas['total'],
        'total_r2' => (int)$ringkas['total_r2'],
        'total_r4' => (int)$ringkas['total_r4'],
        'total_merah' => (int)$ringkas['total_merah'],
        'total_kuning' => (int)$ringkas['total_kuning'],
        'total_putih' => (int)$ringkas['total_putih'],
    ],
    'last_update' => date('Y-m-d H:i:s')
]);
