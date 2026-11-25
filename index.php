<?php
require_once 'config/koneksi.php';

function hitungData($conn, $sql) {
    $result = mysqli_query($conn, $sql);
    $data = mysqli_fetch_assoc($result);
    return $data['total'] ?? 0;
}

$totalJadwal = hitungData(
    $conn,
    "SELECT COUNT(*) AS total FROM jadwal_kuliah"
);

$totalTugas = hitungData(
    $conn,
    "SELECT COUNT(*) AS total FROM tugas"
);

$tugasSelesai = hitungData(
    $conn,
    "SELECT COUNT(*) AS total FROM tugas
     WHERE status = 'Selesai'"
);

$tugasBelum = hitungData(
    $conn,
    "SELECT COUNT(*) AS total FROM tugas
     WHERE status != 'Selesai'"
);

$queryTugas = mysqli_query(
    $conn,
    "SELECT * FROM tugas
     WHERE status != 'Selesai'
     ORDER BY deadline ASC
     LIMIT 5"
);

$queryJadwal = mysqli_query(
    $conn,
    "SELECT * FROM jadwal_kuliah
     ORDER BY FIELD(hari,
     'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'),
     jam_mulai ASC
     LIMIT 5"
);

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Smart Student Planner</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="layout">

    <aside class="sidebar">
        <div class="brand">
            <div class="brand-icon">S</div>
            <div>
                <h2>Smart Planner</h2>
                <small>Student Academic System</small>
            </div>
        </div>

        <p class="menu-label">MENU UTAMA</p>

        <nav>
            <a href="index.php" class="active">▦ Dashboard</a>
            <a href="jadwal/index.php">▣ Jadwal Kuliah</a>
            <a href="tugas/index.php">☷ Daftar Tugas</a>
            <a href="rekomendasi/index.php">✦ Rekomendasi Cerdas</a>
        </nav>

        <div class="sidebar-bottom">
            <p>Smart Student Planner</p>
            <small>Academic Management System</small>
        </div>
    </aside>

    <main class="main-content">

        <header class="topbar">
            <div>
                <h2>Dashboard</h2>
                <p>Selamat datang di Smart Student Planner!</p>
            </div>
            <div class="profile">Mahasiswa</div>
        </header>

        <section class="welcome">
            <div>
                <h1>Halo, Mahasiswa! 👋</h1>
                <p>Kelola jadwal kuliah dan tugasmu dengan lebih teratur.</p>
            </div>
            <div class="welcome-icon">🎓</div>
        </section>

        <section class="stats">

            <div class="stat-card blue">
                <span>Total Jadwal Kuliah</span>
                <h2><?= $totalJadwal ?></h2>
                <small>Jadwal tersimpan</small>
            </div>

            <div class="stat-card purple">
                <span>Total Tugas</span>
                <h2><?= $totalTugas ?></h2>
                <small>Seluruh tugas</small>
            </div>

            <div class="stat-card orange">
                <span>Tugas Belum Selesai</span>
                <h2><?= $tugasBelum ?></h2>
                <small>Perlu dikerjakan</small>
            </div>

            <div class="stat-card green">
                <span>Tugas Selesai</span>
                <h2><?= $tugasSelesai ?></h2>
                <small>Berhasil diselesaikan</small>
            </div>

        </section>

        <section class="content-grid">

            <div class="panel">
                <div class="panel-heading">
                    <div>
                        <h3>Daftar Tugas Terbaru</h3>
                        <p>Tugas yang masih perlu dikerjakan</p>
                    </div>
                    <a href="tugas/index.php">Lihat semua</a>
                </div>

                <?php if (mysqli_num_rows($queryTugas) > 0): ?>

                    <?php while ($tugas = mysqli_fetch_assoc($queryTugas)): ?>

                        <div class="task-item">
                            <div>
                                <strong><?= e($tugas['nama_tugas']) ?></strong>
                                <small><?= e($tugas['mata_kuliah']) ?></small>
                                <small>Deadline: <?= e($tugas['deadline']) ?></small>
                            </div>

                            <span class="status">
                                <?= e($tugas['kesulitan']) ?>
                            </span>
                        </div>

                    <?php endwhile; ?>

                <?php else: ?>

                    <div class="empty">
                        Belum ada tugas yang perlu dikerjakan.
                    </div>

                <?php endif; ?>
            </div>

            <div class="panel">
                <div class="panel-heading">
                    <div>
                        <h3>Jadwal Kuliah</h3>
                        <p>Jadwal perkuliahan tersimpan</p>
                    </div>
                    <a href="jadwal/index.php">Lihat semua</a>
                </div>

                <?php if (mysqli_num_rows($queryJadwal) > 0): ?>

                    <?php while ($jadwal = mysqli_fetch_assoc($queryJadwal)): ?>

                        <div class="schedule-item">
                            <div class="schedule-time">
                                <?= date('H:i', strtotime($jadwal['jam_mulai'])) ?>
                                -
                                <?= date('H:i', strtotime($jadwal['jam_selesai'])) ?>
                            </div>

                            <div>
                                <strong><?= e($jadwal['nama_matkul']) ?></strong>
                                <small>
                                    <?= e($jadwal['hari']) ?> ·
                                    <?= e($jadwal['ruangan'] ?: 'Belum ada ruangan') ?>
                                </small>
                            </div>
                        </div>

                    <?php endwhile; ?>

                <?php else: ?>

                    <div class="empty">
                        Belum ada jadwal kuliah.
                    </div>

                <?php endif; ?>
            </div>

        </section>

        <footer>
            © 2026 Smart Student Planner | Intelligent System
        </footer>

    </main>

</div>

</body>
</html>