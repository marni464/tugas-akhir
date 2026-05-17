<?php
require_once "../konfigurasi/koneksi.php";
wajib_login();

$pesan = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tanggal = $_POST['tanggal'] ?? '';
    $nopol = trim($_POST['nopol'] ?? '');
    $pemilik = trim($_POST['pemilik'] ?? '');
    $jenis_kendaraan = $_POST['jenis_kendaraan'] ?? '';
    $jenis_plat = $_POST['jenis_plat'] ?? '';
    $jenis_layanan = $_POST['jenis_layanan'] ?? '';

    if ($tanggal && $nopol && $pemilik && $jenis_kendaraan && $jenis_plat && $jenis_layanan) {
        $stmt = $koneksi->prepare("INSERT INTO registrasi_stnk (tanggal,nopol,pemilik,jenis_kendaraan,jenis_plat,jenis_layanan) VALUES (?,?,?,?,?,?)");
        $stmt->bind_param("ssssss", $tanggal, $nopol, $pemilik, $jenis_kendaraan, $jenis_plat, $jenis_layanan);
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
    <title>Tambah Data</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body style="background:#eef1df;">
    <div class="d-flex">
        <?php include "../bagian/sidebar.php"; ?>

        <div class="flex-grow-1 p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="m-0">Input Data</h3>
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
                            <input type="date" name="tanggal" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Nopol</label>
                            <input name="nopol" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Pemilik</label>
                            <input name="pemilik" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Jenis Kendaraan</label>
                            <select name="jenis_kendaraan" class="form-select" required>
                                <option value="R2">R2</option>
                                <option value="R4">R4</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Jenis Plat</label>
                            <select name="jenis_plat" class="form-select" required>
                                <option>Merah</option>
                                <option>Kuning</option>
                                <option>Putih</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Jenis Layanan</label>
                            <select name="jenis_layanan" class="form-select" required>
                                <option>BBN1</option>
                                <option>Duplikat</option>
                                <option>Ganti Plat</option>
                                <option>Ganti Nopol</option>
                                <option>BBN2</option>
                                <option>Fiskal</option>
                                <option>Rubah</option>
                            </select>
                        </div>

                        <div class="col-12 d-flex justify-content-end">
                            <button class="btn btn-success px-5">Tambah</button>
                        </div>
                    </form>
                </div>
            </div>

            <?php include "../bagian/footer.php"; ?>
        </div>
    </div>
</body>

</html>