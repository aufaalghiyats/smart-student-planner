<?php
require_once '../config/koneksi.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_tugas = trim($_POST['nama_tugas']);
    $mata_kuliah = trim($_POST['mata_kuliah']);
    $deskripsi = trim($_POST['deskripsi']);
    $deadline = $_POST['deadline'];
    $kesulitan = $_POST['kesulitan'];
    $status = $_POST['status'];

    if (
        $nama_tugas === '' ||
        $mata_kuliah === '' ||
        $deadline === ''
    ) {
        $error = "Nama tugas, mata kuliah, dan deadline wajib diisi.";
    } else {
        $stmt = mysqli_prepare(
            $conn,
            "INSERT INTO tugas (nama_tugas, mata_kuliah, deskripsi, deadline, kesulitan, status)
             VALUES (?, ?, ?, ?, ?, ?)"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "ssssss",
            $nama_tugas,
            $mata_kuliah,
            $deskripsi,
            $deadline,
            $kesulitan,
            $status
        );

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            header("Location: index.php");
            exit;
        } else {
            $error = "Gagal menyimpan tugas.";
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
    <title>Tambah Tugas - Smart Student Planner</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-4">
    <div class="card p-4">
        <h2>Tambah Tugas</h2>
        <p>Masukkan informasi tugas perkuliahan.</p>

        <?php if ($error !== ''): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Nama Tugas</label>
                <input type="text" name="nama_tugas" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Mata Kuliah</label>
                <input type="text" name="mata_kuliah" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="3"></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Deadline</label>
                <input type="datetime-local" name="deadline" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Tingkat Kesulitan</label>
                <select name="kesulitan" class="form-select" required>
                    <option value="Rendah">Rendah</option>
                    <option value="Sedang" selected>Sedang</option>
                    <option value="Tinggi">Tinggi</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="Belum Dikerjakan">Belum Dikerjakan</option>
                    <option value="Sedang Dikerjakan">Sedang Dikerjakan</option>
                    <option value="Selesai">Selesai</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Simpan Tugas</button>
            <a href="index.php" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>

</body>
</html>