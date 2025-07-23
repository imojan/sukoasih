<?php
session_start();
include '../../koneksi.php';

if (!isset($_SESSION["jabatan"])) {
    echo "<script>location='../../login/index.php'</script>";
    exit();
}

$id_dokter = $_GET['id_dokter'];

// Cek apakah id_dokter sudah digunakan di tb_pendaftaran
$cek_pendaftaran = mysqli_query($koneksi, "SELECT 1 FROM tb_pendaftaran WHERE id_dokter = '$id_dokter' LIMIT 1");
if (mysqli_num_rows($cek_pendaftaran) > 0) {
    echo "<script>alert('Dokter sudah digunakan di data Pendaftaran, tidak bisa dihapus.'); location='dokter.php';</script>";
    exit();
}

// Hapus data dokter
$hapus_dokter = mysqli_query($koneksi, "DELETE FROM tb_dokter WHERE id_dokter = '$id_dokter'");

if ($hapus_dokter) {
    echo "<script>alert('Data Dokter berhasil dihapus.');</script>";
} else {
    echo "<script>alert('Gagal menghapus data Dokter.');</script>";
}

echo "<script>location='dokter.php'</script>";
?>
