<?php
require_once "../konfigurasi/koneksi.php";
wajib_login();

$q = trim($_GET['q'] ?? '');
$jenis_kendaraan = $_GET['jenis_kendaraan'] ?? '';
$jenis_plat = $_GET['jenis_plat'] ?? '';
$jenis_layanan = $_GET['jenis_layanan'] ?? '';
$page = max(1, (int)($_GET['page'] ?? 1));
$limit = 5;
$offset = ($page - 1) * $limit;

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
$haveFilters = $q !== '' || $jenis_kendaraan !== '' || $jenis_plat !== '' || $jenis_layanan !== '';

// total data
if ($where) {
    $stmt = $koneksi->prepare("SELECT COUNT(*) c FROM registrasi_stnk $where");
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $total = (int)$stmt->get_result()->fetch_assoc()['c'];
} else {
    $total = (int)$koneksi->query("SELECT COUNT(*) c FROM registrasi_stnk")->fetch_assoc()['c'];
}

$pages = max(1, (int)ceil($total / $limit));

// data per halaman
if ($where) {
    $sql = "SELECT * FROM registrasi_stnk $where ORDER BY tanggal DESC, id DESC LIMIT ? OFFSET ?";
    $stmt = $koneksi->prepare($sql);
    $types2 = $types . "ii";
    $params2 = array_merge($params, [$limit, $offset]);
    $stmt->bind_param($types2, ...$params2);
    $stmt->execute();
    $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
} else {
    $stmt = $koneksi->prepare("SELECT * FROM registrasi_stnk ORDER BY tanggal DESC, id DESC LIMIT ? OFFSET ?");
    $stmt->bind_param("ii", $limit, $offset);
    $stmt->execute();
    $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Pendataan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body style="background:#eef1df;">
    <div class="d-flex">
        <?php include "../bagian/sidebar.php"; ?>

        <div class="flex-grow-1 p-4">
            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="m-0">Pendataan</h3>
                    <a class="btn btn-success" href="tambah.php">Tambah</a>
                </div>

                <form class="row g-2 align-items-end" method="GET">
                    <div class="col-md-4">
                        <label class="form-label mb-1">Cari</label>
                        <input class="form-control" name="q" value="<?= e($q) ?>" placeholder="Cari... (nopol/pemilik/layanan)">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label mb-1">Jenis Kendaraan</label>
                        <select class="form-select" name="jenis_kendaraan">
                            <option value="">Semua Kendaraan</option>
                            <option value="R2" <?= $jenis_kendaraan === 'R2' ? 'selected' : '' ?>>R2</option>
                            <option value="R4" <?= $jenis_kendaraan === 'R4' ? 'selected' : '' ?>>R4</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label mb-1">Jenis Plat</label>
                        <select class="form-select" name="jenis_plat">
                            <option value="">Semua Plat</option>
                            <option value="Merah" <?= $jenis_plat === 'Merah' ? 'selected' : '' ?>>Merah</option>
                            <option value="Kuning" <?= $jenis_plat === 'Kuning' ? 'selected' : '' ?>>Kuning</option>
                            <option value="Putih" <?= $jenis_plat === 'Putih' ? 'selected' : '' ?>>Putih</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label mb-1">Jenis Layanan</label>
                        <select class="form-select" name="jenis_layanan">
                            <option value="">Semua Layanan</option>
                            <option value="BBN1" <?= $jenis_layanan === 'BBN1' ? 'selected' : '' ?>>BBN1</option>
                            <option value="Duplikat" <?= $jenis_layanan === 'Duplikat' ? 'selected' : '' ?>>Duplikat</option>
                            <option value="Ganti Plat" <?= $jenis_layanan === 'Ganti Plat' ? 'selected' : '' ?>>Ganti Plat</option>
                            <option value="Ganti Nopol" <?= $jenis_layanan === 'Ganti Nopol' ? 'selected' : '' ?>>Ganti Nopol</option>
                            <option value="BBN2" <?= $jenis_layanan === 'BBN2' ? 'selected' : '' ?>>BBN2</option>
                            <option value="Fiskal" <?= $jenis_layanan === 'Fiskal' ? 'selected' : '' ?>>Fiskal</option>
                            <option value="Rubah" <?= $jenis_layanan === 'Rubah' ? 'selected' : '' ?>>Rubah</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-grid">
                        <button class="btn btn-outline-dark">Filter</button>
                    </div>
                    <?php if ($haveFilters): ?>
                        <div class="col-md-12">
                            <a href="index.php" class="btn btn-outline-secondary">Reset Filter</a>
                        </div>
                    <?php endif; ?>
                </form>
            </div>

            <div class="mb-3">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-2">
                    <div><strong>Menampilkan <?= count($rows) ?> dari <?= number_format($total) ?> data</strong></div>
                    <?php if ($haveFilters): ?>
                        <div class="text-muted">Filter aktif: <?= trim(($q ? 'q=' . e($q) : '') . ' ' . ($jenis_kendaraan ? 'Kendaraan=' . e($jenis_kendaraan) : '') . ' ' . ($jenis_plat ? 'Plat=' . e($jenis_plat) : '') . ' ' . ($jenis_layanan ? 'Layanan=' . e($jenis_layanan) : '')) ?></div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Nopol</th>
                                    <th>Jenis Kendaraan</th>
                                    <th>Jenis Plat</th>
                                    <th>Jenis Layanan</th>
                                    <th>Pemilik</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($rows as $r): ?>
                                    <tr>
                                        <td><?= e($r['tanggal']) ?></td>
                                        <td><?= e($r['nopol']) ?></td>
                                        <td><b><?= e($r['jenis_kendaraan']) ?></b></td>
                                        <td><b><?= e($r['jenis_plat']) ?></b></td>
                                        <td><b><?= e($r['jenis_layanan']) ?></b></td>
                                        <td><b><?= e($r['pemilik']) ?></b></td>
                                        <td>
                                            <a class="btn btn-sm btn-dark" href="edit.php?id=<?= $r['id'] ?>">Edit</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>

                                <?php if (!$rows): ?>
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">Data belum ada</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex gap-2">
                            <a class="btn btn-secondary px-4" href="unduh_pdf.php?q=<?= urlencode($q) ?>&jenis_kendaraan=<?= urlencode($jenis_kendaraan) ?>&jenis_plat=<?= urlencode($jenis_plat) ?>&jenis_layanan=<?= urlencode($jenis_layanan) ?>" target="_blank">Unduh PDF</a>
                        </div>

                        <nav>
                            <ul class="pagination m-0">
                                <?php
                                $maxPagesToShow = 5;
                                $startPage = max(1, min($page - 2, $pages - $maxPagesToShow + 1));
                                $endPage = min($pages, $startPage + $maxPagesToShow - 1);
                                $baseUrl = '?q=' . urlencode($q) . '&jenis_kendaraan=' . urlencode($jenis_kendaraan) . '&jenis_plat=' . urlencode($jenis_plat) . '&jenis_layanan=' . urlencode($jenis_layanan);
                                ?>

                                <?php if ($page > 1): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="<?= $baseUrl ?>&page=<?= $page - 1 ?>">&laquo;</a>
                                    </li>
                                <?php endif; ?>

                                <?php if ($startPage > 1): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="<?= $baseUrl ?>&page=1">1</a>
                                    </li>
                                    <?php if ($startPage > 2): ?>
                                        <li class="page-item disabled"><span class="page-link">...</span></li>
                                    <?php endif; ?>
                                <?php endif; ?>

                                <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                                    <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                                        <a class="page-link" href="<?= $baseUrl ?>&page=<?= $i ?>"><?= $i ?></a>
                                    </li>
                                <?php endfor; ?>

                                <?php if ($endPage < $pages): ?>
                                    <?php if ($endPage < $pages - 1): ?>
                                        <li class="page-item disabled"><span class="page-link">...</span></li>
                                    <?php endif; ?>
                                    <li class="page-item">
                                        <a class="page-link" href="<?= $baseUrl ?>&page=<?= $pages ?>"><?= $pages ?></a>
                                    </li>
                                <?php endif; ?>

                                <?php if ($page < $pages): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="<?= $baseUrl ?>&page=<?= $page + 1 ?>">&raquo;</a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </nav>
                    </div>

                    <div class="mt-2 text-end">
                        <small class="text-muted">“Lihat lebih banyak” gunakan pagination halaman.</small>
                    </div>
                </div>
            </div>

            <?php include "../bagian/footer.php"; ?>
        </div>
    </div>
</body>

</html>