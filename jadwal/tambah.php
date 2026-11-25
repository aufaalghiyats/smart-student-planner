<?php
require_once '../config/koneksi.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_matkul = trim($_POST['nama_matkul']);
    $hari = $_POST['hari'];
    $jam_mulai = $_POST['jam_mulai'];
    $jam_selesai = $_POST['jam_selesai'];
    $ruangan = trim($_POST['ruangan']);

    if (
        $nama_matkul === '' ||
        $hari === '' ||
        $jam_mulai === '' ||
        $jam_selesai === ''
    ) {
        $error = 'Semua kolom wajib harus diisi!';
    } elseif ($jam_selesai <= $jam_mulai) {
        $error = 'Jam selesai harus setelah jam mulai!';
    } else {
        $stmt = mysqli_prepare(
            $conn,
            "INSERT INTO jadwal_kuliah
            (nama_matkul, hari, jam_mulai, jam_selesai, ruangan)
            VALUES (?, ?, ?, ?, ?)"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "sssss",
            $nama_matkul,
            $hari,
            $jam_mulai,
            $jam_selesai,
            $ruangan
        );

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            header("Location: index.php");
            exit;
        } else {
            $error = 'Gagal menyimpan jadwal kuliah.';
        }

        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Jadwal - Smart Planner</title>
    <link rel="stylesheet" href="../assets/css/style.css">

    <style>
        .form-container {
            background: white;
            padding: 28px;
            border-radius: 13px;
            max-width: 700px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 12px;
            border: 1px solid #dce2ec;
            border-radius: 8px;
            font-size: 13px;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #2563eb;
        }

        .form-actions {
            display: flex;
            gap: 10px;
            margin-top: 25px;
        }

        .btn-save {
            background: #2563eb;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            cursor: pointer;
        }

        .btn-back {
            background: #e2e8f0;
            color: #334155;
            padding: 12px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 13px;
        }

        .error {
            background: #fee2e2;
            color: #b91c1c;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 18px;
            font-size: 13px;
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
                <h2>Tambah Jadwal Kuliah</h2>
                <p>Masukkan informasi jadwal perkuliahan.</p>
            </div>
            <div class="profile">Mahasiswa</div>
        </header>

        <div class="form-container">

            <h3 style="margin-bottom:22px;">
                Form Jadwal Kuliah
            </h3>

            <?php if ($error !== ''): ?>
                <div class="error">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST">

                <div class="form-group">
                    <label>Nama Mata Kuliah</label>
                    <input
                        type="text"
                        name="nama_matkul"
                        placeholder="Contoh: Intelligent System"
                        required
                    >
                </div>

                <div class="form-group">
                    <label>Hari</label>
                    <select name="hari" required>
                        <option value="">Pilih Hari</option>
                        <option>Senin</option>
                        <option>Selasa</option>
                        <option>Rabu</option>
                        <option>Kamis</option>
                        <option>Jumat</option>
                        <option>Sabtu</option>
                        <option>Minggu</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Jam Mulai</label>
                    <input type="time" name="jam_mulai" required>
                </div>

                <div class="form-group">
                    <label>Jam Selesai</label>
                    <input type="time" name="jam_selesai" required>
                </div>

                <div class="form-group">
                    <label>Ruangan</label>
                    <input
                        type="text"
                        name="ruangan"
                        placeholder="Contoh: Lab Komputer 2"
                    >
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-save">
                        Simpan Jadwal
                    </button>

                    <a href="index.php" class="btn-back">
                        Kembali
                    </a>
                </div>

            </form>
        </div>

    </main>
</div>

</body>
</html>