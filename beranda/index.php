<?php
require_once "../konfigurasi/koneksi.php";
wajib_login();

// Agregasi per bulan (bisa kamu ubah per hari jika mau)
$sql = "
SELECT DATE_FORMAT(tanggal, '%Y-%m') periode,
SUM(CASE WHEN jenis_plat='Merah' THEN 1 ELSE 0 END) merah,
SUM(CASE WHEN jenis_plat='Kuning' THEN 1 ELSE 0 END) kuning,
SUM(CASE WHEN jenis_plat='Putih' THEN 1 ELSE 0 END) putih
FROM registrasi_stnk
GROUP BY periode
ORDER BY periode ASC
";

$data = [];
$res = $koneksi->query($sql);
while ($row = $res->fetch_assoc()) $data[] = $row;
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Beranda</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body style="background:#eef1df;">
    <div class="d-flex">
        <?php include "../bagian/sidebar.php"; ?>

        <div class=text-center mt-3>
            <h3>Selamat datang, <?= e(pengguna()['nama']) ?></h3>
            <p class="text-muted">Grafik hasil registrasi secara keseluruhan.</p>

            <div class="card shadow-sm">
                <div class="card-body">
                    <canvas id="grafikBeranda" height="90"></canvas>
                </div>
            </div>

            <p class="text-center mt-3">Website ini berperan sebagai media pendukung pengambilan keputusan dengan menyajikan informasi yang jelas, ringkas, dan mudah dipahami, serta membantu meningkatkan efektivitas kinerja dalam pengelolaan data registrasi STNK.</p>

            <?php include "../bagian/footer.php"; ?>
        </div>
    </div>

    <script>
        const initialRows = <?= json_encode($data) ?>;

        function rowsToChartData(rows) {
            return {
                labels: rows.map(r => r.periode),
                datasets: [{
                        label: 'STNK Merah',
                        data: rows.map(r => Number(r.merah)),
                        tension: .3,
                        borderColor: 'rgb(220,53,69)',
                        backgroundColor: 'rgba(220,53,69,0.2)'
                    },
                    {
                        label: 'STNK Kuning',
                        data: rows.map(r => Number(r.kuning)),
                        tension: .3,
                        borderColor: 'rgb(255,193,7)',
                        backgroundColor: 'rgba(255,193,7,0.2)'
                    },
                    {
                        label: 'STNK Putih',
                        data: rows.map(r => Number(r.putih)),
                        tension: .3,
                        borderColor: 'rgb(33,37,41)',
                        backgroundColor: 'rgba(33,37,41,0.2)'
                    }
                ]
            };
        }

        const ctx = document.getElementById('grafikBeranda');
        let chart = new Chart(ctx, {
            type: 'line',
            data: rowsToChartData(initialRows),
            options: {
                animation: false
            }
        });

        async function refreshBerandaChart() {
            try {
                const res = await fetch('../monitoring/api_data.php');
                const json = await res.json();
                const fetchedRows = json.labels.map((label, idx) => ({
                    periode: label,
                    merah: json.merah[idx] ?? 0,
                    kuning: json.kuning[idx] ?? 0,
                    putih: json.putih[idx] ?? 0
                }));

                chart.data = rowsToChartData(fetchedRows);
                chart.update();
            } catch (err) {
                console.error('Gagal refresh grafik beranda:', err);
            }
        }

        setInterval(refreshBerandaChart, 5000); // update tiap 5 detik
    </script>
    
</body>

</html>