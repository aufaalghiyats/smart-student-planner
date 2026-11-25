<?php
require_once '../config/koneksi.php';

if (
    $_SERVER['REQUEST_METHOD'] !== 'POST' ||
    !isset($_POST['id']) ||
    !ctype_digit($_POST['id'])
) {
    header("Location: index.php");
    exit;
}

$id = (int) $_POST['id'];

$stmt = mysqli_prepare($conn, "DELETE FROM tugas WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);

if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    header("Location: index.php");
    exit;
}

mysqli_stmt_close($stmt);
die("Gagal menghapus tugas.");
?>