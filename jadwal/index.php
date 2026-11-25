<?php
require_once '../config/koneksi.php';

$query = mysqli_query($conn, "SELECT * FROM jadwal_kuliah
ORDER BY FIELD(hari, 'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'), jam_mulai ASC");

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Kuliah - Smart Planner</title>
    <link rel="stylesheet" href="../assets/css/style.css">

    <style>
        .table-container {
            background: white;
            padding: 25px;
            border-radius: 13px;
            overflow-x: auto;
        }

        .btn-add {
            display: inline-block;
            background: #2563eb;
            color: white;
            padding: 11px 17px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 13px;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 600px;
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #edf0f5;
            font-size: 13px;
        }

        th {
            color: #64748b;
            background: #f8fafc;
        }

        .btn-edit, .btn-delete {
            display: inline-block;
            padding: 7px 10px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 11px;
        }

        .btn-edit {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .btn-delete {
            background: #fee2e2;
            color: #dc2626;
            border: none;
            cursor: pointer;
        }

        .page-heading {
            margin-bottom: 25px;
        }

        .page-heading p {
            color: #94a3b8;
            font-size: 13px;
            margin-top: 7px;
        }
    </style>
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
            <a href="../index.php">▦ Dashboard</a>
            <a href="index.php" class="active">▣ Jadwal Kuliah</a>
            <a href="../tugas/index.php">☷ Daftar Tugas</a>
            <a href="../rekomendasi/index.php">✦ Rekomendasi Cerdas</a>
        </nav>

        <div class="sidebar-bottom">
            <p>Smart Student Planner</p>
            <small>Academic Management System</small>
        </div>
    </aside>

    <main class="main-content">

        <header class="topbar">
            <div>
                <h2>Jadwal Kuliah</h2>
                <p>Kelola jadwal perkuliahanmu.</p>
            </div>
            <div class="profile">Mahasiswa</div>
        </header>

        <div class="page-heading">
            <h2>Daftar Jadwal Kuliah</h2>
            <p>Semua jadwal perkuliahan yang telah kamu simpan.</p>
        </div>

        <div class="table-container">

            <a href="tambah.php" class="btn-add">
                + Tambah Jadwal Kuliah
            </a>

            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Mata Kuliah</th>
                        <th>Hari</th>
                        <th>Jam Mulai</th>
                        <th>Jam Selesai</th>
                        <th>Ruangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (mysqli_num_rows($query) > 0): ?>

                        <?php $no = 1; ?>

                        <?php while ($data = mysqli_fetch_assoc($query)): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= e($data['nama_matkul']) ?></td>
                                <td><?= e($data['hari']) ?></td>
                                <td><?= e(substr($data['jam_mulai'], 0, 5)) ?></td>
                                <td><?= e(substr($data['jam_selesai'], 0, 5)) ?></td>
                                <td><?= e($data['ruangan'] ?: '-') ?></td>

                                <td>
                                    <a class="btn-edit"
                                       href="edit.php?id=<?= $data['id'] ?>">
                                        Edit
                                    </a>

                                    <form action="hapus.php"
                                          method="POST"
                                          style="display:inline;"
                                          onsubmit="return confirm('Yakin ingin menghapus jadwal ini?')">

                                        <input type="hidden"
                                               name="id"
                                               value="<?= $data['id'] ?>">

                                        <button type="submit"
                                                class="btn-delete">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>

                    <?php else: ?>
                        <tr>
                            <td colspan="7" style="text-align:center;padding:30px;">
                                Belum ada jadwal kuliah.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

        </div>

        <footer>
            © 2026 Smart Student Planner | Intelligent System
        </footer>

    </main>
</div>

</body>
</html>