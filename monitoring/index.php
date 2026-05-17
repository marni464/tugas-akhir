<?php
require_once "../konfigurasi/koneksi.php";
wajib_login();
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Monitoring</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body style="background:#eef1df;">
    <div class="d-flex">
        <?php include "../bagian/sidebar.php"; ?>

        <div class="flex-grow-1 p-4">
            <div class="row g-3">
                <!-- FILTER -->
                <div class="col-md-3">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5>Filter</h5>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="semua" checked>
                                <label class="form-check-label" for="semua">Semua</label>
                            </div>
                            <hr>

                            <label class="form-label">Periode</label>
                            <input type="date" id="mulai" class="form-control mb-2">
                            <input type="date" id="sampai" class="form-control">

                            <label class="form-label mt-3">Jenis Kendaraan</label>
                            <select id="kendaraan" class="form-select">
                                <option value="">Semua</option>
                                <option value="R2">R2</option>
                                <option value="R4">R4</option>
                            </select>

                            <label class="form-label mt-3">Jenis Plat</label>
                            <select id="plat" class="form-select">
                                <option value="">Semua</option>
                                <option value="Merah">Merah</option>
                                <option value="Kuning">Kuning</option>
                                <option value="Putih">Putih</option>
                            </select>

                            <label class="form-label mt-3">Jenis Layanan</label>
                            <select id="layanan" class="form-select">
                                <option value="">Semua</option>
                                <option>BBN1</option>
                                <option>Duplikat</option>
                                <option>Ganti Plat</option>
                                <option>Ganti Nopol</option>
                                <option>BBN2</option>
                                <option>Fiskal</option>
                                <option>Rubah</option>
                            </select>

                            <button class="btn btn-dark w-100 mt-3" id="btnLihat">Lihat</button>
                            <button class="btn btn-secondary w-100 mt-2" id="btnCetak">Cetak PDF</button>

                        </div>
                    </div>
                </div>

                <!-- GRAFIK -->
                <div class="col-md-9">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h4 class="text-center">Laporan Monitoring</h4>
                            <canvas id="grafikMonitoring" height="90"></canvas>
                        </div>
                    </div>

                    <!-- RINGKASAN -->
                    <div class="card shadow-sm mt-3">
                        <div class="card-body">
                            <h5 class="text-center">Ringkasan Data</h5>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Total Semua Data
                                    <span class="badge bg-primary rounded-pill" id="totalSemua">0</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    STNK Merah
                                    <span class="badge bg-danger rounded-pill" id="totalMerah">0</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    STNK Kuning
                                    <span class="badge bg-warning rounded-pill" id="totalKuning">0</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    STNK Putih
                                    <span class="badge bg-light text-dark rounded-pill" id="totalPutih">0</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <?php include "../bagian/footer.php"; ?>
        </div>
    </div>

    <!-- FORM HIDDEN UNTUK KIRIM BASE64 CHART KE PDF -->
    <form id="formCetak" method="POST" action="cetak_pdf.php" target="_blank" style="display:none;">
        <input type="hidden" name="gambar_grafik" id="gambar_grafik">
        <input type="hidden" name="mulai" id="f_mulai">
        <input type="hidden" name="sampai" id="f_sampai">
        <input type="hidden" name="kendaraan" id="f_kendaraan">
        <input type="hidden" name="plat" id="f_plat">
        <input type="hidden" name="layanan" id="f_layanan">
    </form>

    <script>
        let chart;

        function queryFilter() {
            const semua = document.getElementById('semua').checked;
            const mulai = document.getElementById('mulai').value;
            const sampai = document.getElementById('sampai').value;
            const kendaraan = document.getElementById('kendaraan').value;
            const plat = document.getElementById('plat').value;
            const layanan = document.getElementById('layanan').value;

            // Jika ada filter aktif, override kondisi "Semua" supaya pakai filter
            const pakaiFilter = !semua || mulai || sampai || kendaraan || plat || layanan;
            const q = new URLSearchParams();

            if (pakaiFilter) {
                if (mulai) q.set('mulai', mulai);
                if (sampai) q.set('sampai', sampai);
                if (kendaraan) q.set('kendaraan', kendaraan);
                if (plat) q.set('plat', plat);
                if (layanan) q.set('layanan', layanan);
            }

            return q.toString();
        }

        async function muatData() {
            const qs = queryFilter();
            const url = "api_data.php" + (qs ? ("?" + qs) : "");
            const res = await fetch(url);
            const json = await res.json();

            const labels = json.labels;
            const merah = json.merah;
            const kuning = json.kuning;
            const putih = json.putih;

            if (chart) chart.destroy();
            chart = new Chart(document.getElementById('grafikMonitoring'), {
                type: 'line',
                data: {
                    labels,
                    datasets: [{
                            label: 'STNK Merah',
                            data: merah,
                            tension: .3,
                            borderColor: 'rgb(220,53,69)',
                            backgroundColor: 'rgba(220,53,69,0.2)'
                        },
                        {
                            label: 'STNK Kuning',
                            data: kuning,
                            tension: .3,
                            borderColor: 'rgb(255,193,7)',
                            backgroundColor: 'rgba(255,193,7,0.2)'
                        },
                        {
                            label: 'STNK Putih',
                            data: putih,
                            tension: .3,
                            borderColor: 'rgb(33,37,41)',
                            backgroundColor: 'rgba(33,37,41,0.2)'
                        }
                    ]
                },
                options: {
                    animation: false // supaya stabil saat real-time
                }
            });

            // Update ringkasan
            const ringkas = json.ringkas;
            document.getElementById('totalSemua').textContent = ringkas.total;
            document.getElementById('totalMerah').textContent = ringkas.total_merah;
            document.getElementById('totalKuning').textContent = ringkas.total_kuning;
            document.getElementById('totalPutih').textContent = ringkas.total_putih;
        }

        function isiHiddenFilterUntukCetak() {
            // kalau "Semua" dicentang, kirim kosong supaya dianggap semua
            const semua = document.getElementById('semua').checked;

            document.getElementById('f_mulai').value = semua ? "" : document.getElementById('mulai').value;
            document.getElementById('f_sampai').value = semua ? "" : document.getElementById('sampai').value;
            document.getElementById('f_kendaraan').value = semua ? "" : document.getElementById('kendaraan').value;
            document.getElementById('f_plat').value = semua ? "" : document.getElementById('plat').value;
            document.getElementById('f_layanan').value = semua ? "" : document.getElementById('layanan').value;
        }

        const filterEls = ['mulai', 'sampai', 'kendaraan', 'plat', 'layanan'];
        filterEls.forEach(id => {
            document.getElementById(id).addEventListener('input', () => {
                document.getElementById('semua').checked = false;
            });
        });

        document.getElementById('semua').addEventListener('change', () => {
            if (document.getElementById('semua').checked) {
                filterEls.forEach(id => document.getElementById(id).value = '');
            }
        });

        document.getElementById('btnLihat').addEventListener('click', muatData);

        // Cetak PDF dengan grafik (base64)
        document.getElementById('btnCetak').addEventListener('click', () => {
            if (!chart) {
                alert("Grafik belum dimuat. Klik tombol Lihat dulu.");
                return;
            }
            // ambil gambar dari canvas
            const canvas = document.getElementById('grafikMonitoring');
            const base64 = canvas.toDataURL('image/png', 1.0);

            // isi field hidden
            document.getElementById('gambar_grafik').value = base64;
            isiHiddenFilterUntukCetak();

            // submit ke cetak_pdf.php (tab baru)
            document.getElementById('formCetak').submit();
        });

        // Real-time refresh tiap 5 detik
        setInterval(muatData, 5000);

        // load awal
        muatData();
    </script>
</body>

</html>