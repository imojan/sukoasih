<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION["jabatan"])) {
    echo "<script>location='../login/index.php'</script>";
    exit();
}

$id = mysqli_real_escape_string($koneksi, $_GET['id_pendaftaran']);
$cek = $koneksi->query("SELECT * FROM tb_pemeriksaan WHERE id_pendaftaran='$id'");
if ($cek->num_rows > 0) {
    $koneksi->query("UPDATE tb_pemeriksaan SET status='3' WHERE id_pendaftaran='$id'");
} else {
    $koneksi->query("INSERT INTO tb_pemeriksaan (id_pendaftaran, status) VALUES ('$id', '3')");
}
echo "<script>alert('Data Dibatalkan!');</script>";
echo "<script>location='pendaftaran.php'</script>";

?>