<?php
require_once '../config/koneksi.php';

$query = mysqli_query(
    $conn,
    "SELECT * FROM tugas
     WHERE status != 'Selesai'
     ORDER BY deadline ASC"
);

function e($value) {
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function hitungPrioritas($deadline, $kesulitan) {
    $sekarang = time();
    $batasWaktu = strtotime($deadline);

    $sisaDetik = $batasWaktu - $sekarang;
    $sisaHari = (int) ceil($sisaDetik / 86400);

    if ($sisaHari <= 1) {
        $prioritas = 3;
    } elseif ($sisaHari <= 3) {
        $prioritas = 2;
    } else {
        $prioritas = 1;
    }

    if ($kesulitan === 'Tinggi') {
        $prioritas = min(3, $prioritas + 1);
    }

    if ($prioritas === 3) {
        return ['Tinggi', 'Segera kerjakan tugas ini.'];
    } elseif ($prioritas === 2) {
        return ['Sedang', 'Sebaiknya mulai dikerjakan.'];
    } else {
        return ['Rendah', 'Masih memiliki waktu untuk mengerjakan.'];
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekomendasi - Smart Student Planner</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container-fluid">
    <div class="row">

        <aside class="col-md-2 p-3 bg-dark text-white min-vh-100">
            <h4>Smart Planner</h4>
            <hr>
            <a href="../index.php" class="text-white d-block mb-3">Dashboard</a>
            <a href="../jadwal/index.php" class="text-white d-block mb-3">Jadwal Kuliah</a>
            <a href="../tugas/index.php" class="text-white d-block mb-3">Daftar Tugas</a>
            <a href="index.php" class="text-white d-block mb-3">Rekomendasi</a>
        </aside>

        <main class="col-md-10 p-4">

            <h2>Rekomendasi Prioritas Tugas</h2>
            <p>
                Sistem menentukan prioritas berdasarkan deadline
                dan tingkat kesulitan tugas.
            </p>

            <div class="card p-3 mt-4">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Nama Tugas</th>
                                <th>Mata Kuliah</th>
                                <th>Deadline</th>
                                <th>Kesulitan</th>
                                <th>Prioritas</th>
                                <th>Rekomendasi</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if (mysqli_num_rows($query) > 0): ?>
                                <?php $no = 1; ?>

                                <?php while ($row = mysqli_fetch_assoc($query)): ?>
                                    <?php
                                    $hasil = hitungPrioritas(
                                        $row['deadline'],
                                        $row['kesulitan']
                                    );

                                    $prioritas = $hasil[0];
                                    $pesan = $hasil[1];

                                    $warna = 'success';

                                    if ($prioritas === 'Tinggi') {
                                        $warna = 'danger';
                                    } elseif ($prioritas === 'Sedang') {
                                        $warna = 'warning';
                                    }
                                    ?>

                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= e($row['nama_tugas']) ?></td>
                                        <td><?= e($row['mata_kuliah']) ?></td>
                                        <td><?= e($row['deadline']) ?></td>
                                        <td><?= e($row['kesulitan']) ?></td>
                                        <td>
                                            <span class="badge bg-<?= $warna ?>">
                                                <?= e($prioritas) ?>
                                            </span>
                                        </td>
                                        <td><?= e($pesan) ?></td>
                                    </tr>

                                <?php endwhile; ?>

                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center">
                                        Belum ada tugas yang perlu dikerjakan.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card p-3 mt-4">
                <h5>Aturan Sistem (Rule-Based System)</h5>
                <ul>
                    <li>Deadline 1 hari atau kurang: prioritas Tinggi.</li>
                    <li>Deadline 2–3 hari: prioritas Sedang.</li>
                    <li>Deadline lebih dari 3 hari: prioritas Rendah.</li>
                    <li>Kesulitan Tinggi menaikkan prioritas satu tingkat.</li>
                    <li>Tugas berstatus Selesai tidak ditampilkan.</li>
                </ul>
            </div>

        </main>
    </div>
</div>

</body>
</html>