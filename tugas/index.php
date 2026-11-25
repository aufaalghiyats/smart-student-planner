<?php
require_once '../config/koneksi.php';

$query = mysqli_query($conn, "SELECT * FROM tugas ORDER BY deadline ASC");

function e($value) {
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Tugas - Smart Student Planner</title>
    <link rel="stylesheet" href="../assets/css/style.css">
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
            <a href="index.php" class="text-white d-block mb-3">Daftar Tugas</a>
            <a href="../rekomendasi/index.php" class="text-white d-block mb-3">Rekomendasi</a>
        </aside>

        <main class="col-md-10 p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2>Daftar Tugas</h2>
                    <p>Kelola tugas dan deadline perkuliahan.</p>
                </div>
                <a href="tambah.php" class="btn btn-primary">+ Tambah Tugas</a>
            </div>

            <div class="card p-3">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Nama Tugas</th>
                                <th>Mata Kuliah</th>
                                <th>Deadline</th>
                                <th>Kesulitan</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($query) > 0): ?>
                                <?php $no = 1; ?>
                                <?php while ($row = mysqli_fetch_assoc($query)): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= e($row['nama_tugas']) ?></td>
                                        <td><?= e($row['mata_kuliah']) ?></td>
                                        <td><?= e($row['deadline']) ?></td>
                                        <td><?= e($row['kesulitan']) ?></td>
                                        <td><?= e($row['status']) ?></td>
                                        <td>
                                            <a href="edit.php?id=<?= (int)$row['id'] ?>"
                                               class="btn btn-warning btn-sm">Edit</a>

                                            <form action="hapus.php" method="POST"
                                                  class="d-inline"
                                                  onsubmit="return confirm('Yakin ingin menghapus tugas ini?')">
                                                <input type="hidden" name="id"
                                                       value="<?= (int)$row['id'] ?>">
                                                <button type="submit"
                                                        class="btn btn-danger btn-sm">
                                                    Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center">
                                        Belum ada tugas.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</div>

</body>
</html>