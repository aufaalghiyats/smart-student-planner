<?php
require_once '../config/koneksi.php';

if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = (int) $_GET['id'];

$stmt = mysqli_prepare($conn, "SELECT * FROM tugas WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$tugas = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$tugas) {
    die("Data tugas tidak ditemukan.");
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_tugas = trim($_POST['nama_tugas']);
    $mata_kuliah = trim($_POST['mata_kuliah']);
    $deskripsi = trim($_POST['deskripsi']);
    $deadline = $_POST['deadline'];
    $kesulitan = $_POST['kesulitan'];
    $status = $_POST['status'];

    if ($nama_tugas === '' || $mata_kuliah === '' || $deadline === '') {
        $error = "Nama tugas, mata kuliah, dan deadline wajib diisi.";
    } else {
        $stmt = mysqli_prepare(
            $conn,
            "UPDATE tugas SET nama_tugas=?, mata_kuliah=?, deskripsi=?, deadline=?, kesulitan=?, status=? WHERE id=?"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "ssssssi",
            $nama_tugas,
            $mata_kuliah,
            $deskripsi,
            $deadline,
            $kesulitan,
            $status,
            $id
        );

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            header("Location: index.php");
            exit;
        } else {
            $error = "Gagal memperbarui tugas.";
        }

        mysqli_stmt_close($stmt);
    }
}

function e($value) {
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

$deadlineValue = date('Y-m-d\TH:i', strtotime($tugas['deadline']));
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Tugas - Smart Student Planner</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-4">
    <div class="card p-4">
        <h2>Edit Tugas</h2>
        <p>Perbarui informasi tugas perkuliahan.</p>

        <?php if ($error !== ''): ?>
            <div class="alert alert-danger"><?= e($error) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Nama Tugas</label>
                <input type="text" name="nama_tugas" class="form-control"
                       value="<?= e($tugas['nama_tugas']) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Mata Kuliah</label>
                <input type="text" name="mata_kuliah" class="form-control"
                       value="<?= e($tugas['mata_kuliah']) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="3"><?= e($tugas['deskripsi']) ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Deadline</label>
                <input type="datetime-local" name="deadline" class="form-control"
                       value="<?= e($deadlineValue) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Tingkat Kesulitan</label>
                <select name="kesulitan" class="form-select">
                    <?php foreach (['Rendah', 'Sedang', 'Tinggi'] as $level): ?>
                        <option value="<?= $level ?>"
                            <?= $tugas['kesulitan'] === $level ? 'selected' : '' ?>>
                            <?= $level ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <?php foreach (['Belum Dikerjakan', 'Sedang Dikerjakan', 'Selesai'] as $s): ?>
                        <option value="<?= $s ?>"
                            <?= $tugas['status'] === $s ? 'selected' : '' ?>>
                            <?= $s ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="index.php" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>

</body>
</html>