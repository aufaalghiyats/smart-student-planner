<?php
require_once '../config/koneksi.php';

if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = (int) $_GET['id'];

$stmt = mysqli_prepare($conn, "SELECT * FROM jadwal_kuliah WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$data) {
    header("Location: index.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_matkul = trim($_POST['nama_matkul']);
    $hari = $_POST['hari'];
    $jam_mulai = $_POST['jam_mulai'];
    $jam_selesai = $_POST['jam_selesai'];
    $ruangan = trim($_POST['ruangan']);

    if ($nama_matkul === '' || $hari === '' ||
        $jam_mulai === '' || $jam_selesai === '') {
        $error = "Semua kolom wajib harus diisi!";
    } elseif ($jam_selesai <= $jam_mulai) {
        $error = "Jam selesai harus setelah jam mulai!";
    } else {
        $stmt = mysqli_prepare(
            $conn,
            "UPDATE jadwal_kuliah
             SET nama_matkul = ?, hari = ?, jam_mulai = ?,
                 jam_selesai = ?, ruangan = ?
             WHERE id = ?"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "sssssi",
            $nama_matkul,
            $hari,
            $jam_mulai,
            $jam_selesai,
            $ruangan,
            $id
        );

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            header("Location: index.php");
            exit;
        } else {
            $error = "Gagal memperbarui jadwal.";
        }

        mysqli_stmt_close($stmt);
    }
}

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Jadwal - Smart Planner</title>
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
                <h2>Edit Jadwal Kuliah</h2>
                <p>Perbarui informasi jadwal perkuliahan.</p>
            </div>
            <div class="profile">Mahasiswa</div>
        </header>

        <div class="form-container">

            <h3 style="margin-bottom:22px;">Form Edit Jadwal</h3>

            <?php if ($error !== ''): ?>
                <div class="error"><?= e($error) ?></div>
            <?php endif; ?>

            <form method="POST">

                <div class="form-group">
                    <label>Nama Mata Kuliah</label>
                    <input type="text" name="nama_matkul"
                        value="<?= e($data['nama_matkul']) ?>" required>
                </div>

                <div class="form-group">
                    <label>Hari</label>
                    <select name="hari" required>
                        <?php
                        $hariList = [
                            'Senin','Selasa','Rabu','Kamis',
                            'Jumat','Sabtu','Minggu'
                        ];
                        foreach ($hariList as $hari):
                        ?>
                            <option value="<?= e($hari) ?>"
                                <?= $data['hari'] === $hari ? 'selected' : '' ?>>
                                <?= e($hari) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Jam Mulai</label>
                    <input type="time" name="jam_mulai"
                        value="<?= e(substr($data['jam_mulai'], 0, 5)) ?>"
                        required>
                </div>

                <div class="form-group">
                    <label>Jam Selesai</label>
                    <input type="time" name="jam_selesai"
                        value="<?= e(substr($data['jam_selesai'], 0, 5)) ?>"
                        required>
                </div>

                <div class="form-group">
                    <label>Ruangan</label>
                    <input type="text" name="ruangan"
                        value="<?= e($data['ruangan']) ?>">
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-save">
                        Simpan Perubahan
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