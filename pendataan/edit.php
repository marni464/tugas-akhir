<?php
require_once "../konfigurasi/koneksi.php";
wajib_login();

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    header("Location: index.php");
    exit;
}

$pesan = "";

// Ambil data
$stmt = $koneksi->prepare("SELECT * FROM registrasi_stnk WHERE id=? LIMIT 1");
$stmt->bind_param("i", $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();
if (!$data) {
    header("Location: index.php");
    exit;
}

// Update saat POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tanggal = $_POST['tanggal'] ?? '';
    $nopol = trim($_POST['nopol'] ?? '');
    $pemilik = trim($_POST['pemilik'] ?? '');
    $jenis_kendaraan = $_POST['jenis_kendaraan'] ?? '';
    $jenis_plat = $_POST['jenis_plat'] ?? '';
    $jenis_layanan = $_POST['jenis_layanan'] ?? '';

    if ($tanggal && $nopol && $pemilik && $jenis_kendaraan && $jenis_plat && $jenis_layanan) {
        $stmt = $koneksi->prepare("
      UPDATE registrasi_stnk
      SET tanggal=?, nopol=?, pemilik=?, jenis_kendaraan=?, jenis_plat=?, jenis_layanan=?
      WHERE id=?
    ");
        $stmt->bind_param("ssssssi", $tanggal, $nopol, $pemilik, $jenis_kendaraan, $jenis_plat, $jenis_layanan, $id);
        $stmt->execute();
        header("Location: index.php");
        exit;
    } else {
        $pesan = "Semua field wajib diisi.";
    }
}
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Edit Data</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body style="background:#eef1df;">
    <div class="d-flex">
        <?php include "../bagian/sidebar.php"; ?>

        <div class="flex-grow-1 p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="m-0">Edit Data</h3>
                <a class="btn btn-outline-dark" href="index.php">← Kembali</a>
            </div>

            <?php if ($pesan): ?>
                <div class="alert alert-danger"><?= e($pesan) ?></div>
            <?php endif; ?>

            <div class="card shadow-sm">
                <div class="card-body">
                    <form method="POST" class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Tanggal</label>
                            <input type="date" name="tanggal" class="form-control" value="<?= e($data['tanggal']) ?>" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Nopol</label>
                            <input name="nopol" class="form-control" value="<?= e($data['nopol']) ?>" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Pemilik</label>
                            <input name="pemilik" class="form-control" value="<?= e($data['pemilik']) ?>" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Jenis Kendaraan</label>
                            <select name="jenis_kendaraan" class="form-select" required>
                                <option value="R2" <?= $data['jenis_kendaraan'] === 'R2' ? 'selected' : '' ?>>R2</option>
                                <option value="R4" <?= $data['jenis_kendaraan'] === 'R4' ? 'selected' : '' ?>>R4</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Jenis Plat</label>
                            <select name="jenis_plat" class="form-select" required>
                                <option <?= $data['jenis_plat'] === 'Merah' ? 'selected' : '' ?>>Merah</option>
                                <option <?= $data['jenis_plat'] === 'Kuning' ? 'selected' : '' ?>>Kuning</option>
                                <option <?= $data['jenis_plat'] === 'Putih' ? 'selected' : '' ?>>Putih</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Jenis Layanan</label>
                            <select name="jenis_layanan" class="form-select" required>
                                <?php
                                $opsi = ['BBN1', 'Duplikat', 'Ganti Plat', 'Ganti Nopol', 'BBN2', 'Fiskal', 'Rubah'];
                                foreach ($opsi as $o) {
                                    $sel = ($data['jenis_layanan'] === $o) ? 'selected' : '';
                                    echo "<option $sel>" . e($o) . "</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="col-12 d-flex justify-content-end">
                            <button class="btn btn-dark px-5">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>

            <?php include "../bagian/footer.php"; ?>
        </div>
    </div>
</body>

</html>