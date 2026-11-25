<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "smart_student_planner";

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>