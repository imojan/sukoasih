<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include '../../koneksi.php';

if (!isset($_SESSION["jabatan"])) {
    echo "<script>location='../../login/index.php'</script>";
    exit();
}

$no_rm = $_GET['no_rm'];

$cek = mysqli_query($koneksi, "SELECT * FROM tb_pendaftaran WHERE no_rm = '$no_rm'");
if (mysqli_num_rows($cek) > 0) {
    echo "<script>alert('Pasien sudah pernah Terdaftar, tidak bisa dihapus.'); location='pasien.php';</script>";
    exit();
}

$cek = mysqli_query($koneksi, "SELECT * FROM tb_pemeriksaan WHERE no_rm = '$no_rm'");
if (mysqli_num_rows($cek) > 0) {
    echo "<script>alert('Pasien sudah pernah melakukan Pemeriksaan, tidak bisa dihapus.'); location='pasien.php';</script>";
    exit();
}

// Baru hapus data pasien
$hapus_pasien = $koneksi->query("DELETE FROM tb_pasien WHERE no_rm = '$no_rm'");

if ($hapus_pasien) {
    echo "<script>alert('Data Pasien Terhapus!');</script>";
} else {
    echo "<script>alert('Gagal menghapus data pasien!');</script>";
}

echo "<script>location='pasien.php'</script>";
?>