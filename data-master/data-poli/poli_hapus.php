<?php
session_start();
include '../../koneksi.php';

if (!isset($_SESSION["jabatan"])) {
    echo "<script>location='../../login/index.php'</script>";
    exit();
}

$id_poli = $_GET['id_poli'];

// Cek di tb_dokter
$cek = mysqli_query($koneksi, "SELECT * FROM tb_dokter WHERE id_poli = '$id_poli'");
if (mysqli_num_rows($cek) > 0) {
    echo "<script>alert('Poli sudah digunakan oleh Dokter, tidak bisa dihapus.'); location='poli.php';</script>";
    exit();
}

// Cek di tb_pendaftaran
$cek = mysqli_query($koneksi, "SELECT * FROM tb_pendaftaran WHERE id_poli = '$id_poli'");
if (mysqli_num_rows($cek) > 0) {
    echo "<script>alert('Poli sudah digunakan di Pendaftaran, tidak bisa dihapus.'); location='poli.php';</script>";
    exit();
}

// Cek di tb_pemeriksaan
$cek = mysqli_query($koneksi, "SELECT * FROM tb_pemeriksaan WHERE id_poli = '$id_poli'");
if (mysqli_num_rows($cek) > 0) {
    echo "<script>alert('Poli sudah digunakan di Pemeriksaan, tidak bisa dihapus.'); location='poli.php';</script>";
    exit();
}

// Hapus jika tidak terkait
$hapus_poli = $koneksi->query("DELETE FROM tb_poli WHERE id_poli='$id_poli'");

if ($hapus_poli) {
    echo "<script>alert('Data Poli Terhapus!');</script>";
} else {
    echo "<script>alert('Gagal menghapus data poli!');</script>";
}

echo "<script>location='poli.php'</script>";
?>
